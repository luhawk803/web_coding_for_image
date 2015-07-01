import os 
import sys,shutil
import shlex, subprocess
import argparse
import glob,time
import re,csv
import io,timeit
#folder name for the Geovision channel number, such as if cam_list=[0,4,3,2,1], so channel 1 goes to _date_kidid/cam04/ folder
camera_list='[0,7,8,3,4,5,6,1,2]'
From_dir='E:/data/'
MULTI_dir='E:/multisensory'
#subject log 
SUBJECT_TXT='E:/subject.txt'
AUD_list=[1,2]
#csv file
CSVFILENAME='E:/subject_proc.csv'



def main():
# input check
	parser = argparse.ArgumentParser(description='This is a script by Hao.')
	parser.add_argument('-s','--subject_id',help='subject ID Several digitals', required=True)
	parser.add_argument('-c','--cam_list',help='folder name for the Geovision channel number, such as if cam_list=[0,4,3,2,1], so channel 1 goes to _date_kidid/cam04/ folder. Default=[0,7,8,3,4,5,6,1,2]',type=str, nargs='?',default=camera_list)
	args = parser.parse_args()
	if len(sys.argv)==2 and sys.argv[1]=="--help":
		print "--help MENU"
		sys.exit(1)
	if len(sys.argv) not in [2,3,5]:
		print "Wrong number arguments, detail see in --help"
		sys.exit(1)	
	print "Progress is :\n\n"
	print "The subject ID is:\t\t",args.subject_id
	print "The camera list is:\t\t",args.cam_list
	if (len(args.subject_id)>4) or (not (args.subject_id.isdigit())):
		print "\n\nERROR subject_id input"
		exit(1)
	global CAM_LIST
	CAM_LIST=Get_cam_list(args.cam_list)
		
	#CSV check 
	if not os.path.isfile(CSVFILENAME):
		exit('Error missing '+CSVFILENAME)
	else:
		sid_csvrow=Csv_check(args.subject_id,7)
	
#creat the dir 
	sub_dir,old_dir=Creat_subject_folder(args.subject_id)
#move the data from after_conv
	Mov_syc_to_formal(sub_dir,old_dir,args.subject_id)
	
	Csv_add(args.subject_id,sid_csvrow)	
	exit('OK! Build_subject_dir.py is done!')



def Mov_syc_to_formal(ndir,odir,sid):
	date,exp_id,kid_id=Get_Sub_Info(sid)	
	#move info file first
	o_make_file=glob.glob(odir[:-10]+'__'+date+'_'+kid_id+'_info.txt')
	if len(o_make_file)!=1:
		exit('Error happen to get the info file, name not match or no *info.txt file')	
	else:
		shutil.copy2(o_make_file[0], ndir)
	#move avi and wav files to formal folder
	for file in glob.glob(odir+'/*'):
		print file
		if (file[-3:].upper()=='AVI') or (file[-3:].upper()=='MOV'):
			video_list=[]
			#print file,'----v'
			if file[-6:-4].isdigit():
				cam=int(file[-6:-4])
				video_list.append(cam)
			elif file[-5].isdigit():
				cam=int(file[-5])
				video_list.append(cam)
			else:
				exit('Error happen for '+file+". Not match the name stander!")
			cam_folder=ndir+"/cam"+str(CAM_LIST[cam]).zfill(2)+'_video_r/'
			frame_folder=ndir+"/cam"+str(CAM_LIST[cam]).zfill(2)+'_frames_p/'
			if os.path.exists(frame_folder)==False:
				os.mkdir(frame_folder,0755)
			if os.path.exists(cam_folder)==False:
				os.mkdir(cam_folder,0755)
			shutil.copy2(file,cam_folder+'/__'+date+'_'+kid_id+'_cam'+str(CAM_LIST[cam]).zfill(2)+file[-4:])
		elif (file[-3:].upper()=='WAV') or (file[-3:].upper()=='MP3'):
			#print file,'-------a'
			audio_list=[]
			if file[-6:-4].isdigit():
				cam=int(file[-6:-4])
				audio_list.append(cam)
			elif file[-5].isdigit():
				cam=int(file[-5])
				audio_list.append(cam)
			else:
				exit('Error happen for '+file+". Not match the name stander!")
			AUD_folder=ndir+"/speech_r/"
			#AUD_folder=ndir+"/cam"+str(CAM_LIST[cam]).zfill(2)+'_audio_r/'
			if os.path.exists(AUD_folder)==False:
				os.mkdir(AUD_folder,0755)			
			shutil.copy2(file,AUD_folder+'/cam'+str(cam).zfill(2)+file[-4:])
		elif (file[-4:].upper()=='.TXT') and (file[-25:-22].upper()=='ALL') :	
			#print file,'----info'
			motion_folder=ndir+'/position_sensor_r/'
			if os.path.exists(motion_folder)==False:
				os.mkdir(motion_folder,0755)			
			shutil.copy2(file,motion_folder)			
		else:
			#print file,'------else'
			shutil.copy2(file,ndir+'/')
		
		
def Creat_subject_folder(sid):
	date,exp_id,kid_id=Get_Sub_Info(sid)
	if os.path.exists(MULTI_dir)==False:
		os.mkdir(MULTI_dir,0755)
	exp_dir=MULTI_dir+"/experiment_"+exp_id
	if os.path.exists(exp_dir)==False:
		os.mkdir(exp_dir,0755)
	include_dir=exp_dir+'/included'
	if os.path.exists(include_dir)==False:
		os.mkdir(include_dir,0755)
	sub_dir=include_dir+'/__'+date+'_'+kid_id
	if os.path.exists(sub_dir)==False:
		os.mkdir(sub_dir,0755)
	if os.path.exists(sub_dir+'/extra_p/')==False:
		os.mkdir(sub_dir+'/extra_p/',0755)
	if os.path.exists(sub_dir+'/derived/')==False:
		os.mkdir(sub_dir+'/derived/',0755)
	if os.path.exists(sub_dir+'/speech_transcription_p/')==False:
		os.mkdir(sub_dir+'/speech_transcription_p/',0755)
	else:
		print 'Already have folder: '+sub_dir
		flag=0
		while flag==0:
			s=raw_input('Type (Enter) to contuine, or \'q\'  to quit the program: ')
			if s =='':
				flag=1
			elif s =='q':
				exit('Halt by user, data transfer is not done')
			else:
				print 'error input please try again.'
	old_dir=From_dir+exp_id+"/__"+date+'_'+kid_id+'/after_conv'
	if os.path.exists(old_dir)==False:
		exit(sid +'syc folder no longer exist')
	return sub_dir,old_dir
	
#turen the str list to actural list
def Get_cam_list(argv):
	vtemplist=[]
	if argv[0]!='[' or argv[-1]!=']':
		exit('\nError video or audio input format, check \'-h\'!!')
	vtemplist=[int(x.strip()) for x in argv[1:-1].split(',')]
	return vtemplist
	
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
	
	
def Csv_check(s_id,num_syn):
	csvrfile =csv.reader(open(CSVFILENAME, "r"))	
	for ii in csvrfile:
		if len(ii)>0:
			if ii[0]==s_id :
				for jj in range(4,num_syn+1):
					if ii[jj]!='1':
						exit('Error'+s_id+' have not pass previous step yet, OR you need manually change '+CSVFILENAME+' check the yarbus slot')
				for jj in range(num_syn+1,len(ii)):
					if ii[jj]!='0':
						exit('Error'+s_id+' passed later step OR you need manually change '+CSVFILENAME)	
				sid_csvrow=ii	
	return sid_csvrow
			
def Csv_add(s_id,row):			
	csvfile =csv.reader(open(CSVFILENAME, "rb"))
	csvfilelist=[l for l in csvfile]
	num_ind=csvfilelist.index(row)
	csvfilelist[num_ind][8]='1'
	csvwfile =csv.writer(open(CSVFILENAME, "w"))
	csvwfile.writerows(csvfilelist)	
	
	
if __name__ == "__main__":
	__author__ = 'Hao Lu'
	start=timeit.default_timer()
	main()
	stop=timeit.default_timer()
	print 'Time cost(sec): ',stop - start