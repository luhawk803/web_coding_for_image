import os 
import sys,shutil
import shlex, subprocess
import argparse
import glob,time,csv
import re,datetime
import io
import scipy , numpy
from scipy.misc import imread
from scipy.linalg import norm
from scipy import sum, average
import timeit
#cam number need to be extracted
EX_video_list='[1,2,3,4,5,6]'
MULTI_dir='E:/multisensory'
SUBJECT_TXT='E:/subject.txt'
#csv file
CSVFILENAME='E:/subject_proc.csv'
#image compare file
COMPAREIMAGE='ScriptFileNotMove.jpg'

def main():

#__1.1__________________________________read from infoTXT version
	# AVI_FILE_LIST=[]#keep the all file which moved by program
	# parser = argparse.ArgumentParser(description='This is a script by Hao.')
	# parser.add_argument('-s','--subject_id',help='subject ID Several digitals', required=True)
	# args = parser.parse_args()
	# date,exp_id,kid_id=Get_Sub_Info(args.subject_id)
	
	# if len(sys.argv)==2 and sys.argv[1]=="--help":
		# print "--help MENU"
		# sys.exit(1)
	# if (len(sys.argv)!=3):
		# print "Wrong number arguments, detail see in --help"
		# sys.exit(1)	
	# print "Progress is :\n"
	# print "The subject ID is:\t\t",args.subject_id
	# folder_dir=MULTI_dir+'/experiment_'+exp_id+'/included/__'+date+'_'+kid_id+'/'
	# info_file=open(folder_dir+'__'+date+'_'+kid_id+'_info.txt')
	# blist,elist=Get_BE_info_list(info_file)
#--end 1.1---------------------------------	


#__1.2__________________________________input B&E frame version
	AVI_FILE_LIST=[]#keep the all file which moved by program
	parser = argparse.ArgumentParser(description='This is a script by Hao.Input example should be: python extract_frames.py -s 3201 -b [3000,8000,12000] -e [5000,10000,15000].')
	parser.add_argument('-b','--begin', help='trials beging number [1000,4000,10000,20000]. Start with 1',required=True)
	parser.add_argument('-e','--end',help='trials beging number [3000,8000,15000,30000]\n final range would be:  1000-3000, 4000-8000, 10000-15000, 20000-30000', required=True)
	parser.add_argument('-s','--subject_id',help='subject ID Several digitals', required=True)
	parser.add_argument('-x','--extract_list',help='camera number you want to extract',type=str, nargs='?',default=EX_video_list)	
	parser.add_argument('-r','--replac_blue',help='0 is not check, whether replace the blue frames',type=int, nargs='?',default=1)
	args = parser.parse_args()
	
	if len(sys.argv)==2 and sys.argv[1]=="--help":
		print "--help MENU"
		sys.exit(1)
	if len(sys.argv) not in [2,7,9,11]:
		print "Wrong number arguments, detail see in --help"
		sys.exit(1)	
	print "Progress is :\n\n"
	print "Trials begin frame number is:\t\t",args.begin
	print "Trials end frame number is :\t\t",args.end
	print "The subject ID is:\t\t",args.subject_id
	print "The camera gonna extract is:\t\t",args.extract_list
	print "whether replace the blue frames:\t\t",args.replac_blue
	
	
	global ex_video_list
	ex_video_list=Get_cam_list(args.extract_list)
	blist,elist=Get_BE_list(args.begin,args.end)
#--end 1.2--------------------------------------------
	
	
	#CSV check 
	if not os.path.isfile(CSVFILENAME):
		exit('Error missing '+CSVFILENAME)
	else:
		sid_csvrow=Csv_check(args.subject_id,7)
	
	
# check the input begin and end frame number	
	date,exp_id,kid_id=Get_Sub_Info(args.subject_id)
	check_be_list(blist,elist)
	folder_dir=MULTI_dir+'/experiment_'+exp_id+'/included/__'+date+'_'+kid_id+'/'
	info_filelist=glob.glob(folder_dir+'*_info.txt')
	if len(info_filelist)>1:
		exit('Error \'info.txt\' ending file under dir: '+ folder_dir)
	folder_dir_list=[]
	for ii in ex_video_list:
		folder_dir_list.append(folder_dir+'cam'+str(ii).zfill(2)+'_video_r')
	frame_dir_list=[]
	for folder in folder_dir_list:
		if folder[-10:-8].isdigit():
			tempnum=int(folder[-10:-8])	
			frame_dir=folder[:-7]+'frames_p'
			if  os.path.exists(frame_dir)==False:
				os.mkdir(frame_dir,0755)
			avifilename=glob.glob(folder+'/*.mov')
			if len(avifilename)==1:
	#			extract(avifilename[0],frame_dir)
				print 'DO NOT type anything unless back to terminal'
				frame_dir_list.append(frame_dir)
	print 'Still processing...'
	#Change_num_jpg(frame_dir_list,min(blist),max(elist))
	print 'Still processing...'
	#Del_temp_img_file(frame_dir_list)
	
	# #replace the blue frame
	if args.replac_blue:
		print 'Check the blue frame...'
		#Replace_blue_frame(frame_dir_list)
		Replace_blue_frame_java(frame_dir_list)
	
	#cut the audio file 
	time_str=str(datetime.timedelta(seconds=blist[0]/30.0))
	print 'cut audio time ',time_str
	for ii in glob.glob(folder_dir+'speech_r/cam*.wav'):
		Cut_audio(ii, ii.split('.wav')[0]+'_after_cut.wav',time_str)
		if  os.path.exists(folder_dir+'speech_r/sub/')==False:
			os.mkdir(folder_dir+'speech_r/sub/',0755)
		shutil.move(ii,folder_dir+'speech_r/sub/')
		if ii[-5].isdigit() and int(ii[-5])!=2 :			
			shutil.move(ii.split('.wav')[0]+'_after_cut.wav',folder_dir+'speech_r/sub/')
			
		#os.remove(ii)
		
	# update info.txt
	infofile=open(info_filelist[0],'r')
	infofilelines=infofile.readlines()
	infofile.close()
	speechtimeindex=infofilelines.index('# speech\n')+3
	trialindex=infofilelines.index('# trial number, onset, offset\n')+1
	videotimeindex=infofilelines.index('# video cam03\n')+2
	oldtimelist=infofilelines[speechtimeindex].split(',')
	newtime=str(datetime.timedelta(seconds=blist[0]/30)+datetime.timedelta(seconds=(int(oldtimelist[0])*3600+int(oldtimelist[1])*60+int(oldtimelist[2])+int(oldtimelist[3])/1000.0))).split(':')
	
	
	infofilelines[speechtimeindex]=newtime[0].zfill(2)+','+newtime[1].zfill(2)+','+newtime[2].split('.')[0].zfill(2)+','+newtime[2].split('.')[1][:3]+',44100\n'
	infofilelines[videotimeindex]=newtime[0].zfill(2)+','+newtime[1].zfill(2)+','+newtime[2].split('.')[0].zfill(2)+','+newtime[2].split('.')[1][:3]+',30\n'
		#input trial
	for ii in range(len(blist)):
		infofilelines.insert(trialindex+ii,str(ii+1)+','+str(blist[ii]+1-blist[0])+','+str(elist[ii]-blist[0]+1)+'\n')
	
	
	winfofile=open(info_filelist[0],'w')
	winfofile.writelines(infofilelines)
	winfofile.close()
	
	
	#update csv file
	Csv_add(args.subject_id,sid_csvrow,blist[0])
	exit('Extract frame done!!')
	
	
#video convert for the AVI to MOV, for yarbus.
def Cut_audio(oldfilename,newfilename,time_str):	
	commandline="ffmpeg -i "+oldfilename +" -ss "+time_str +" -acodec copy "+ newfilename
	subprocess.call(commandline.split(' '))
	
def Get_cam_list(argv):
	vtemplist=[]
	if argv[0]!='[' or argv[-1]!=']':
		exit('\nError video or audio input format, check \'-h\'!!')
	vtemplist=[int(x.strip()) for x in argv[1:-1].split(',')]
	return vtemplist	

def Replace_blue_frame_java(frame_dir_list):
	for frame_dir in frame_dir_list:
		check_blue_java(frame_dir)
	
def check_blue_java(avifilename,jpg_dir):
	commandline="ffmpeg -i "+avifilename +' -r 30 -q:v 1 -f image2 '+ jpg_dir+'/%d.jpg'
	subprocess.call(commandline.split(' '))
	
	
	
def Replace_blue_frame(frame_dir_list):
	for frame_dir in frame_dir_list:
		for ii in glob.glob(frame_dir+'/img_*.jpg'):			
			if Issameimage(ii, COMPAREIMAGE):
				print 'find blue frame',ii
				frameindex=int(ii.split('_')[-1].split('.jpg')[0])
				if os.path.isfile(frame_dir+'/img_'+str(frameindex+1)+'.jpg'):
					os.remove(ii)
					shutil.copy(frame_dir+'/img_'+str(frameindex+1)+'.jpg',ii)
				elif os.path.isfile(frame_dir+'/img_'+str(frameindex-1)+'.jpg'):
					os.remove(ii)
					shutil.copy(frame_dir+'/img_'+str(frameindex-1)+'.jpg',ii)
				else:
					exit('Error, no frame before or after '+ii)

# check whether it is the blue frame
def Issameimage(file1,file2):
	print file1
	# read images as 2D arrays (convert to grayscale for simplicity)
	img1 = to_grayscale(imread(file1).astype(float))
	img2 = to_grayscale(imread(file2).astype(float))
	# compare
	diff = img1 - img2  
	return sum(abs(diff))/img1.size<1				
def to_grayscale(arr):
	"If arr is a color image (3D array), convert it to grayscale (2D array)."
	if len(arr.shape) == 3:
		return average(arr, -1)  # average over the last axis (color channels)
	else:
		return arr				
				
def Csv_check(s_id,num_syn):
	csvrfile =csv.reader(open(CSVFILENAME, "r"))	
	for ii in csvrfile:
		if len(ii)>0:
			if ii[0]==s_id :
				for jj in range(4,num_syn+1):
					if ii[jj]!='1':
						exit('Error'+s_id+' have not pass previous step yet, OR you need manually change '+CSVFILENAME)
				sid_csvrow=ii	
	return sid_csvrow
			
def Csv_add(s_id,row,frist_org_tral_num):			
	csvfile =csv.reader(open(CSVFILENAME, "rb"))
	csvfilelist=[l for l in csvfile]
	num_ind=csvfilelist.index(row)
	csvfilelist[num_ind][9]='1'
	csvfilelist[num_ind].append(str(frist_org_tral_num))
	csvwfile =csv.writer(open(CSVFILENAME, "w"))
	csvwfile.writerows(csvfilelist)	
	
	
def Del_temp_img_file(flist):
	for dir in flist:
		for ii in glob.glob(dir+'/[1-9]*.jpg'):
			os.remove(ii)
			
def Change_num_jpg(flist, b_num,e_num):
	for dir in flist:
		for ii in range(b_num,e_num+1):
			o_img=glob.glob(dir+'/'+str(ii)+'.jpg')
			if len(o_img)!=1:
				exit('Error happend for' + dir+str(ii)+'.jpg' )
			else:
				os.rename(o_img[0],dir+'/img_'+str(ii-b_num+1)+'.jpg')

def extract(avifilename,jpg_dir):
	commandline="ffmpeg -i "+avifilename +' -r 30 -q:v 1 -f image2 '+ jpg_dir+'/%d.jpg'
	subprocess.call(commandline.split(' '))

def	check_be_list(blist,elist):
	if len(blist)!=len(elist):
		exit('Input beging frame and end frame is wrong')
	sublist=[elist[i]-blist[i]	for i in range(len(blist))]
	for i in sublist:
		if i <=0:
			exit('Error input beging fram & end frame number')
		
	
def Get_BE_list(argv,arga):
	vtemplist=[]
	atemplist=[]
	templist=''
	if argv[0]!='[' or arga[0]!='[' or argv[-1]!=']' or arga[-1]!=']':
		exit('\nError begin or end frame format!!')
	vtemplist=[int(x.strip()) for x in argv[1:-1].split(',')]
	atemplist=[int(x.strip()) for x in arga[1:-1].split(',')]	
	return vtemplist,atemplist

def	Get_Sub_Info(sid):
	ins = open( SUBJECT_TXT, "r" )
	array = []
	for line in ins:
		array.append( line.split())	
	Nflag=[]
	for ii in range(len(array)):
		if sid==array[ii][0]:
			Nflag.append(array[ii])
	if len(Nflag)==0:
		sys.exit("Subject_id =="+sid+" is not in added in the "+SUBJECT_TXT)
	if len(Nflag)>1:
		sys.exit("Subject_id =="+sid+" has too many lines in "+SUBJECT_TXT)
	date=Nflag[0][2]
	exp_id=Nflag[0][1]
	kid_id=Nflag[0][3]
	return date, exp_id, kid_id	

def Get_BE_info_list(file):	
	blist=[]
	elist=[]
	lines=file.readlines()
	for ii in range(6,12):
		linelist=[line.strip().split(',') for line in lines[ii].split('\n') if line.strip()]
		if len(linelist)==1:
			blist.append(int(linelist[0][1]))
			elist.append(int(linelist[0][2]))
		else:
			break
	return blist, elist
if __name__ == "__main__":
	__author__ = 'Hao Lu'
	start=timeit.default_timer()
	main()
	stop=timeit.default_timer()
	print 'Time cost(sec): ',stop - start