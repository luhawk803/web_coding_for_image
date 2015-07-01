import os 
import sys,shutil
import shlex
import argparse
import glob,time
import re,csv
import io
import timeit
#data location on BACKUS
MAINPATH='E:/'
#subject log 
SUBJECT_TXT='E:/subject.txt'
#data sourse from TURING machine
RAW_DATA_DIR='Y:/'
#csv table header
TABLE_HEAD=['sub_id','exp_id','date','kid_id','is_creat','is_syc','is_conv','is_yarbus','is_build','is_extract','extract_start_num']
#csv file
CSVFILENAME=MAINPATH+'subject_proc.csv'


def main():
	AVI_FILE_LIST=[]#keep the all file which moved by program
	parser = argparse.ArgumentParser(description='This is a script by Hao.')
	parser.add_argument('-s','--subject_ID', help='subject ID XXXX: four digits number',required=True)
	args = parser.parse_args()
	
	
	if len(sys.argv)==2 and sys.argv[1]=="--help":
		print "--help MENU"
		sys.exit(1)
	if (len(sys.argv)!=3):
		print "Wrong number arguments, detail see in --help"
		sys.exit(1)	
	
	date,exp_id,kid_id=Get_Sub_Info(args.subject_ID)

	#print date,exp_id, kid_id
	if not glob.glob(os.path.join(RAW_DATA_DIR+args.subject_ID, '*.*')):
		exit('No '+args.subject_ID+' folder under '+RAW_DATA_DIR)
	sub_raw_dir=Gen_dir(date,exp_id,kid_id)
	Get_data_from_turing(sub_raw_dir,args.subject_ID)
	
	#update csv
	
	if not os.path.isfile(CSVFILENAME):
		csvfile = csv.writer(open(CSVFILENAME, "wb"))
		csvfile.writerow(TABLE_HEAD)
		csvfile.writerow([args.subject_ID,date,exp_id,kid_id,'1','0','0','0','0','0'])
	else:
		Csv_check_and_add(args.subject_ID, date,exp_id,kid_id)
	exit("OK! creat_dir is done")

def Csv_check_and_add(s_id,date,e_id,k_id):
	csvrfile =csv.reader(open(CSVFILENAME, "r"))	
	for ii in csvrfile:
		if len(ii)>0:
			if ii[0]==s_id:
				exit('Error \"subject_proc.csv\" already has subject ID:'+s_id)
	csvwfile =csv.writer(open(CSVFILENAME, "a"))
	csvwfile.writerow([s_id,date,e_id,k_id,'1','0','0','0','0','0','0'])			
			
	
	
def Get_data_from_turing(to_dir,sub_id):
	print "Move data"
	print "TURING\t\t\tTO\t\t\tBACKUS"
	i=0
	for filename in glob.glob(os.path.join(RAW_DATA_DIR+sub_id, '*.*')):
		i=i+1
		shutil.copy(filename, to_dir)
		print filename+"\t\t"+to_dir
	print 'Move',i," files"
	
	
	
# generate the dir for the raw data on BACKUS 
def Gen_dir(date,exp_id,kid_id):
	if os.path.exists(MAINPATH+'data')==False:
		os.mkdir(MAINPATH+'data',0755)
		
	EXPdir=MAINPATH+'data/'+exp_id
	if os.path.exists(EXPdir)==False:
		os.mkdir(EXPdir,0755)
	
	Datedir=EXPdir+'/__'+date+'_'+kid_id
	if os.path.exists(Datedir)==False:
		os.mkdir(Datedir,0755)
	
	Rawdir=Datedir+'/raw_data/'
	if os.path.exists(Rawdir)==False:
		os.mkdir(Rawdir,0755)
	else:
		exit("Already have "+Rawdir+" on BACKUS")		
	return Rawdir
	
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