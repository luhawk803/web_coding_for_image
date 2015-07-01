import  os 
import sys,shutil
import shlex,subprocess
import argparse
import glob,time
import re,csv
import io,timeit
MAINDIR='E:/data/'
#subject log 
SUBJECT_TXT='E:/subject.txt'
#csv file
CSVFILENAME='E:/subject_proc.csv'

def main():
#	AVI_FILE_LIST=[]#keep the all file which moved by program
	parser = argparse.ArgumentParser(description='This is a script by Hao.')
	parser.add_argument('-s','--subject_id',help='subject ID Several digitals', required=True)
	parser.add_argument('-v','--video',help='Convert video number only can be 1-16 as follow: [1,2,3,4,5,6]', required=True)
	parser.add_argument('-a','--audio',help='Extract audio number of the channel as follow: [1,2,3]', required=True)
	args = parser.parse_args()
 
	if len(sys.argv)==2 and sys.argv[1]=="--help":
		print "--help MENU"
		sys.exit(1)
	if (len(sys.argv)!=7):
		print "Wrong number arguments, detail see in --help"
		sys.exit(1)	
	print "Progress is :\n\n"
	print "The subject ID is:\t\t",args.subject_id
	print "Which videos gonna convert:\t",args.video
	print "Which channel audio extract:\t",args.audio
	date,exp_id,kid_id=Get_Sub_Info(args.subject_id)
	
	#CSV check 
	if not os.path.isfile(CSVFILENAME):
		exit('Error missing '+CSVFILENAME)
	else:
		sid_csvrow=Csv_check(args.subject_id,5)
	
	
	newdir,olddir,oldfilelist=Gen_syc_conv_dir(date,exp_id,kid_id)
	videolist,audiolist=Get_audvid_list(args.video,args.audio)
	avifilelist,avinamelist=Get_avi_list(olddir)
	Copy_no_avifile(list(set(oldfilelist)-set(avifilelist)),newdir)
	audio_map_list=Gen_map_list(audiolist,avinamelist,olddir,newdir)
	video_map_list=Gen_map_list(videolist,avinamelist,olddir,newdir)
	duriation_time=getLength(avifilelist)
	
	
	#print audio_map_list,video_map_list	
	for ii in video_map_list:
		#print ii
		AVI2MOV(ii[1],ii[2]+".mov",duriation_time)
	for ii in audio_map_list:
		#print ii
		MOV2WAV(ii[1],ii[2]+".wav")

		
	#update csv file
	Csv_add(args.subject_id,sid_csvrow)	
	exit("OK! convert_video_to_mov.py  is  done")	
	
def getLength(filelist):
	final=[]
	for filename in filelist:
		result = subprocess.Popen(["ffprobe", filename],
		stdout = subprocess.PIPE, stderr = subprocess.STDOUT)
		#return result.stdout.readlines()
		final.append([x for x in result.stdout.readlines() if "Duration" in x][0].split(',')[0].split()[1])
	time_list=[]

	for ss in final:
		ii=ss.split(':')
		time_list.append(int(ii[0])*3600+int(ii[1])*60+float(ii[2]))
	min_time=int(min(time_list)-5)
	min_time_str=str(int(min_time/3600)).zfill(2)+':'+str(int(min_time%3600)/60).zfill(2)+':'+str(int(min_time%60)).zfill(2)
	return min_time_str

def Csv_check(s_id,num_syn):
	csvrfile =csv.reader(open(CSVFILENAME, "r"))	
	for ii in csvrfile:
		if len(ii)>0:
			if ii[0]==s_id :
				for jj in range(4,num_syn+1):
					if ii[jj]!='1':
						exit('Error'+s_id+' have not pass previous step yet, OR you need manually change '+CSVFILENAME)
				for jj in range(num_syn+1,len(ii)):
					if ii[jj]!='0':
						exit('Error'+s_id+' passed later step OR you need manually change '+CSVFILENAME)	
				sid_csvrow=ii	
	return sid_csvrow
			
def Csv_add(s_id,row):			
	csvfile =csv.reader(open(CSVFILENAME, "rb"))
	csvfilelist=[l for l in csvfile]
	num_ind=csvfilelist.index(row)
	csvfilelist[num_ind][6]='1'
	csvwfile =csv.writer(open(CSVFILENAME, "w"))
	csvwfile.writerows(csvfilelist)	
	
	
def Gen_map_list(nlist,filelist,olddir,newdir):
	maplist=[]
	for ii in nlist:
		newlist=[ii]
		for jj in filelist:
			if jj[-6:-4].isdigit():
				if ii==int(jj[-6:-4]):
					newlist.append(olddir+jj)
					newlist.append(newdir+jj[:-4])
			else:
				if ii==int(jj[-5]):
					newlist.append(olddir+jj)
					newlist.append(newdir+jj[:-4])
		if len(newlist)!=3:
			exit("Wrong convert list"+str(ii))
		maplist.append(newlist)
		print newlist
	return maplist
	
def	Copy_no_avifile(files,dir):
	for file in files:
		shutil.copy(file, dir)
	
	
def Get_avi_list(dir):
	alist=[]
	blist=[]
	for file in glob.glob(dir+"*.Avi"):
		alist.append(file)
		blist.append(file.split('after_syc\\')[1])
	if not alist:
		exit('Error check your after_syc folder!!!')
	return alist,blist
	

def Get_audvid_list(argv,arga):
	vtemplist=[]
	atemplist=[]
	templist=''
	if argv[0]!='[' or arga[0]!='[' or argv[-1]!=']' or arga[-1]!=']':
		exit('\nError video or audio input format, check \'-h\'!!')
	vtemplist=[int(x.strip()) for x in argv[1:-1].split(',')]
	atemplist=[int(x.strip()) for x in arga[1:-1].split(',')]	
	return vtemplist,atemplist
	
# check is there any syc folder, generate the dir for the convert_video_syc data 
def Gen_syc_conv_dir(date,exp_id,kid_id):
	EXPdir=MAINDIR+exp_id
	Datedir=EXPdir+'/__'+date+'_'+kid_id
	SYCdir=Datedir+'/after_syc/'
	if os.path.exists(SYCdir)==False:
		exit("ERROR no synchronize data in folder: "+ SYCdir)
	oldfilelist=[]
	for name in glob.glob(SYCdir+"*"):
		oldfilelist.append(name)
	After_conv_dir=Datedir+'/after_conv/'
	if os.path.exists(After_conv_dir)==False:
		os.mkdir(After_conv_dir,0755)
	#else:
	#	exit("Already have "+After_conv_dir+" on BACKUS")		
	return After_conv_dir,SYCdir,oldfilelist

# based on the subject_id, find the kidID, date, expid at SUBJECT.TXT file	
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
 
#video convert for the AVI to MOV, for yarbus.
def AVI2MOV(avifilename,movfilename,duriation_time):
	commandline="ffmpeg -i "+avifilename +" -r 30 -qscale 0 -vcodec mpeg4 -acodec copy -t "+ duriation_time+" -f mov "+ movfilename
	subprocess.call(commandline.split(' '))
	
#extract audio from the video 
def MOV2WAV(avifilename,audiofilename):
	commandline="ffmpeg -i "+avifilename +" -acodec copy "+ audiofilename
	subprocess.call(commandline.split(' '))
	
	
	
if __name__ == "__main__":
	__author__ = 'Hao Lu'
	start=timeit.default_timer()
	main()
	stop=timeit.default_timer()
	print 'Time cost(sec): ',stop - start