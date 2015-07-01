import  os 
import sys,shutil
import shlex,subprocess
import argparse, math
import glob,time
import re,csv
import io,timeit
MAINDIR='E:/data/'
#subject log 
SUBJECT_TXT='E:/subject.txt'
#csv file
CSVFILENAME='E:/subject_proc.csv'
INFOTEMP=['#\n', '# This file has been generated automatically by a python script "synchronize_video.py"\n', '#\n', '\n', '# trial info\n', '# trial number, onset, offset\n',  '\n', '# sensor\n', '# recording time\n', '# hour, min, sec, ms\n', '00,00,00,000\n', '\n', '# speech\n', '# recording time\n', '# hour, min, sec, ms\n', '00,00,00,00,44100\n', '\n', '# video cam03\n', '# hour, min, sec, ms, rate\n', '00,00,00,000,30\n']

def main():
#	AVI_FILE_LIST=[]#keep the all file which moved by program
	parser = argparse.ArgumentParser(description='This is a script by Hao. ')
	parser.add_argument('-s','--subject_id',help='subject ID Several digitals', required=True)
	args = parser.parse_args()
 
	if len(sys.argv)==2 and sys.argv[1]=="--help":
		print "--help MENU"
		sys.exit(1)
	if (len(sys.argv)!=3):
		print "Wrong number arguments, detail see in --help"
		sys.exit(1)	
	print "Progress is :\n\n"
	print "The subject ID is:\t\t",args.subject_id
	date,exp_id,kid_id=Get_Sub_Info(args.subject_id)
	
	#CSV check 
	if not os.path.isfile(CSVFILENAME):
		exit('Error missing '+CSVFILENAME)
	else:
		sid_csvrow=Csv_check(args.subject_id,4)
		
		
	after_syc_dir,raw_dir,raw_files,avilist,F_avi_name,sub_dir=Gen_raw_syc_dir(date,exp_id,kid_id)
	start_list,latest_time=Get_start_time(raw_dir)
	cutlist=Compute_cut_frame_num(start_list)	
	if max(cutlist)>=25:
		exit("Error, something wrong with the start time, have 20 frames difference")
	if  len(avilist)!=len(cutlist):
		exit('Error the video files number is not equal to raw_video_time.txt video number')
	for ii in list(set(raw_files)-set(avilist)):
		shutil.copy2(ii,after_syc_dir)
	
	#make info
	infotemp=INFOTEMP
	sensor_index_temp=infotemp.index('# sensor\n')
	sensor_file_name=glob.glob(raw_dir+"/all"+date+'*.txt')[0]
	sensor_time_str=sensor_file_name.split('_')[-1]
	infotemp[sensor_index_temp+3]=sensor_time_str[0:2]+','+sensor_time_str[2:4]+','+sensor_time_str[4:6]+','+sensor_time_str[6:9]+'\n'
	
	speech_index_temp=infotemp.index('# speech\n')
	infotemp[speech_index_temp+3]=latest_time[0:2]+','+latest_time[3:5]+','+latest_time[6:8]+','+latest_time[9:12]+',44100\n'

	video_index_temp=infotemp.index('# video cam03\n')
	infotemp[video_index_temp+2]=latest_time[0:2]+','+latest_time[3:5]+','+latest_time[6:8]+','+latest_time[9:12]+',30\n'
	
	
	infofile = open(sub_dir+'/__'+date+'_'+kid_id+'_info.txt', "w+")
	infofile.writelines(infotemp)

	#cut	
	
	for ii in range(len(avilist)):
		num=Get_num(avilist[ii][-6:-4])
		movfile=after_syc_dir+F_avi_name+str(num).zfill(2)+'.avi'
		#print avilist
		if cutlist[num-1]!=0:
			cut_video(avilist[ii],movfile,cutlist[num-1])
		else:
			shutil.copy2(avilist[ii],movfile)
			
			
	#update csv file
	Csv_add(args.subject_id,sid_csvrow)	
	exit("Synchronize_video is  done")
	
	
	

def Csv_check(s_id,num_syn):
	csvrfile =csv.reader(open(CSVFILENAME, "r"))	
	for ii in csvrfile:
		if len(ii)>1:
			if ii[0]==s_id :
				for jj in range(4,num_syn+1):
					if ii[jj]!='1':
						exit('Error'+s_id+' have not pass creat_dir.py yet, OR you need manually change '+CSVFILENAME)
				for jj in range(num_syn+1,len(ii)):
					if ii[jj]!='0':
						exit('Error'+s_id+' passed synchronize_video.py OR you need manually change '+CSVFILENAME)		
				sid_csvrow=ii	
	return sid_csvrow
			
def Csv_add(s_id,row):			
	csvfile =csv.reader(open(CSVFILENAME, "rb"))
	csvfilelist=[l for l in csvfile]
	num_ind=csvfilelist.index(row)
	csvfilelist[num_ind][5]='1'
	csvwfile =csv.writer(open(CSVFILENAME, "w"))
	csvwfile.writerows(csvfilelist)		

	
def Get_num(xx):
	if (not xx[0].isdigit()):
		yy=xx[1]
	elif (not xx[1].isdigit()):
		exit('Error about the name of the cam file of raw_data!!')
	else:
		yy=xx
	return int(yy)	
	
#video convert for the AVI to MOV, for yarbus.
def cut_video(avifilename,movfilename,fdiff):
	stime="00:00:00."+str(fdiff*(1.0/30.0))[2:5]	
	commandline="ffmpeg -i "+avifilename +" -ss "+stime +" -vcodec mpeg4 -qscale 0 -acodec copy -f avi "+ movfilename
	print commandline
	subprocess.call(commandline.split(' '))
	
	
# check is there any syc folder, generate the dir for the convert_video_syc data 
def Gen_raw_syc_dir(date,exp_id,kid_id):
	EXPdir=MAINDIR+exp_id
	Datedir=EXPdir+'/__'+date+'_'+kid_id	
	rdir=Datedir+'/raw_data/'
	if os.path.exists(rdir)==False:
		exit("ERROR no raw data folder "+ rdir)
	oldfilelist=[]
	avilist=[]
	for name in glob.glob(rdir+"*"):
		oldfilelist.append(name)
		if name[-4:].upper()=='.AVI':
			avilist.append(name)
	F_avi_name='File'+date+(avilist[0].split('File'+date)[1]).split('_cam')[0]+'_cam'
	
	After_syc_dir=Datedir+'/after_syc/'
	if os.path.exists(After_syc_dir)==False:
		os.mkdir(After_syc_dir,0755)
	#else:
	#	exit("Already have "+After_syc_dir+" on BACKUS")		
	return After_syc_dir,rdir,oldfilelist,avilist,F_avi_name,Datedir
	
#comput cut frame for each videos
def Compute_cut_frame_num(start_list):
	Tmax=max(start_list[2:])*1000
	cutlist=[int(round((Tmax-ii*1000)/(100/3))) for ii in start_list]
	cutlist_final=[]
	for ii in cutlist:
		if ii>=0:
			cutlist_final.append(ii)
		else:
			cutlist_final.append(0)
	return cutlist_final
	
	
# get the time from raw_video_time.txt	
def Get_start_time(rdir):
	Tfile=rdir+'raw_video_time.txt'
	if (not os.path.isfile(Tfile)):
		exit('Error no'+ Tfile)
	ins = open(Tfile, "r" )
	lines=ins.readlines()
	array=[]
	for ii in range(1,len(lines)):
		if (lines[ii][0].isdigit() and int(lines[ii][0])==ii):
			array.append((lines[ii].split())[0])
	temp=[]
	for ii in  array:
		temp.append( [ float(ii) for ii in ((ii.split(','))[1].split(':'))])
	Tlist=[]
	for ii in temp:
		Tlist.append(ii[0]*3600+ii[1]*60+ii[2])
	return Tlist,array[Tlist.index(max(Tlist))].split(',')[1]
	
def Get_raw_dir(date,exp_id,kid_id):
	rdir=MAINDIR+exp_id+'/__'+date+'_'+kid_id+'/raw_data/'
	if os.path.exists(rdir)==False:
		exit("ERROR no raw data folder "+ rdir)
	return rdir

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
 

	

	
	
	
if __name__ == "__main__":
	__author__ = 'Hao Lu'
	start=timeit.default_timer()
	main()
	stop=timeit.default_timer()
	print 'Time cost(sec): ',stop - start