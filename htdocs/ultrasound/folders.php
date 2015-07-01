<!DOCTYPE html>
<html>
<?php
function countjpgfiles($dir){
	$res=count(glob($dir."/*.jpg"));
    $ffs = scandir($dir);
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..'){
            if(is_dir($dir.'/'.$ff)) $res=$res+ countjpgfiles($dir.'/'.$ff);
        }
    }
   return $res;}
   
function diskto($folderlist)
   {    
	$res;
	foreach ( $folderlist as $ff)
		{
		$res[end(explode("/", $ff))]=countjpgfiles($ff);		
		}
	return $res;
   
   }

?>
<body>


 <?php #admin/restricted.php 
           // #####[make sure you put this code before any html output]#####

// //starting the session
 session_start();
 echo "Hi ",$_SESSION['username'];
//checking if a log SESSION VARIABLE has been set
  if( !isset($_SESSION['log']) || ($_SESSION['log'] != 'in') ){
        // //if the user is not allowed, display a message and a link to go back to login page
	echo "You are not allowed.";
	echo '<a href="index.php">back to login page</a>';

        // //then abort the script
	 exit();
 }
   ####  CODE FOR LOG OUT #### 
if(isset($_GET['log']) && ($_GET['log']=='out')){
        //if the user logged out, delete any SESSION variables
	session_destroy();
	
        //then redirect to login page
	header('location:index.php');
}//end log out

?> 
 <!-- RESTRICTED PAGE HTML GOES HERE -->
 <!-- add a LOGOUT link before the form -->
<form method="get" action="#">
  <p><button type="submit" name="log" value="out" style="position: absolute; top: 0; right: 0;"> log out</a></button> </p> 
 </form>

 <!-- <h5 style="color: red">Speaker0001 to speaker0004 are just practice.</h5>  -->
 <!-- <h5 style="color: red">Speaker0005 to speaker0008 are for drawing the whole tongue contours.</h5> -->
 <!-- <h5 style="color: red">Speaker0009 to speaker0012 are for defining the tongue internal coordinate system (3 points / 2 lines).</h5>  -->
 <!-- <h5 style="color: red">Speaker0013 was temporary and is now deleted.</h5> -->
 <!-- <h5 style="color: red">Speaker0014 is for drawing the whole tongue contours on the CMU ARCTIC dataset.</h5> -->
 <!-- <h5 style="color: red">Speaker0015 is for defining the tongue internal coordinate system (3 points / 2 lines) on the CMU ARCTIC dataset.</h5> -->
 <!-- <h5 style="color: red">Speaker0016 to Speaker0019 is for drawing the whole tongue contours on the 3D coronal and sagittal datasets from 2014 summer students (in this order: MY-cor, CT-cor, CT-sag, MY-sag)</h5> -->
 <!-- <h5 style="color: red">Speaker0020 is for drawing the whole tongue contours on the Fonetika/HUN dataset.</h5> -->
 <!-- <h5 style="color: red">Speaker0021 is for defining the tongue internal coordinate system (3 points / 2 lines) on the Fonetika/HUN dataset.</h5> -->
 <!-- <h5 style="color: red">Speaker0022 to Speaker 0029 is for drawing sagittal contours on the 3D / Spanish / Speaker 1 dataset (datasets 1-8 out of 8; files 1-7, 9).</h5> -->
 <h5 style="color: red">Speaker0030 to Speaker0033 is for tracing the coronal slices of the tongue and palate on the 3D / Spanish / Speaker 1 dataset (datasets 1-4 out of 8).</h5>
 <!-- <h5 style="color: red">Speaker0034 to Speaker0037 is for tracing the diaphragm for the Tidal Breathing 1-4 tasks (from Lulich et al 2014 ASA study) in the horizontal plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0038 to Speaker 0039 is for tracing the diaphragm for the Gettysburg 1-2 tasks (from Lulich et al 2014 ASA study) in the horizontal plane.</h5> -->
 <h5 style="color: red">Speaker0040 to Speaker0042 is for tracing the diaphragm for the Conversation 1a-c tasks (from Lulich et al 2014 ASA study) in the horizontal plane.</h5>
 <!-- <h5 style="color: red">Speaker0043 is for tracing the diaphragm for the Conversation 2 task (from Lulich et al 2014 ASA study) in the horizontal plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0044 is for tracing the diaphragm for the FVC task (from Lulich et al 2014 ASA study) in the horizontal plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0045 is for tracing the diaphragm for the SVC task (from Lulich et al 2014 ASA study) in the horizontal plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0046 to Speaker0049 is for tracing the diaphragm for the Tidal Breathing 1-4 tasks (from Lulich et al 2014 ASA study) in the vertical plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0050 to Speaker 0051 is for tracing the diaphragm for the Gettysburg 1-2 tasks (from Lulich et al 2014 ASA study) in the vertical plane.</h5> -->
 <h5 style="color: red">Speaker0052 to Speaker0054 is for tracing the diaphragm for the Conversation 1a-c tasks (from Lulich et al 2014 ASA study) in the vertical plane.</h5>
 <!-- <h5 style="color: red">Speaker0055 is for tracing the diaphragm for the Conversation 2 task (from Lulich et al 2014 ASA study) in the vertical plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0056 is for tracing the diaphragm for the FVC task (from Lulich et al 2014 ASA study) in the vertical plane.</h5> -->
 <!-- <h5 style="color: red">Speaker0057 is for tracing the diaphragm for the SVC task (from Lulich et al 2014 ASA study) in the vertical plane.</h5> -->
 <h5 style="color: red">Speaker0058 to Speaker0061 is for tracing the coronal tongue + palate (from the Janssen and Lulich 2014 ASA study).</h5>
 <h5 style="color: red">Speaker0062 to Speaker0065 is for tracing the coronal tongue + palate (from the Lulich 2014 ASA ultrasound vowel study).</h5>
 <h5 style="color: red">Speaker0066, Speaker0068, Speaker0070, Speaker0072 are for tracing the sagittal tongue + palate (from the Janssen and Lulich ASA study).</h5>
 <h5 style="color: red">Speaker0067, Speaker0069, Speaker0071, Speaker0073 are for tracing the sagittal tongue + palate (from the Lulich 2014 ASA vowel study).</h5>
 <h5 style="color: red">Speaker0074 to Speaker0077 is for tracing the sagittal tongue + palate (3D / Spanish / Speaker 1 datasets 1-4).</h5>
 

 <br/>

<?php
	if(isset($_GET['folder']) ){
		$tmp_cur_folder=$_GET['folder'];
		$_SESSION['speaker']=$_GET['folder'];
		echo "<a href=\"files.php\"><font size=\"5\">Click, if not move to next page, $tmp_cur_folder</font></a>"
		header('location:files.php');
	}
	$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

	//select db
	mysqli_select_db($dbc, 'ultrasound_new') or die('no db connection');
	$tablename=$_SESSION['username'];
	//$tablename=strtoupper($tablename);
	
	
	
	
	
	//doing 
	$q="SELECT spk_id, COUNT(spk_id) FROM $tablename where coors is not null group by spk_id;" ;
	//echo $q;
	//$q="SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=1; ";
	//step3: run the query and store result	
	$res = mysqli_query($dbc, $q);
	$rownum=0;
	$spk2casenum=array_filter(glob('E:/ultrasoundimage/speaker*'), 'is_dir');
	$diskspk=diskto($spk2casenum);
	
	if (!$res)
	{}
	else 
		{
		$resultstring;
		while ($row=mysqli_fetch_row($res))
			{
			//echo '<pre>'; print_r($row); echo '<pre/>';
			
				$rownum+=1;
				$temp=$row[1]/$diskspk[$row[0]];			
				//echo "<p  style=\"color:gray;\"><a href=\"?folder=$row[0]\">$row[0]</a> <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress>$row[1]/{$diskspk[$row[0]]}</p>";
				$resultstring[$row[0]] = "<p  style=\"color:gray;\"><a href=\"?folder=$row[0]\">$row[0]</a> <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress>$row[1]/{$diskspk[$row[0]]}</p>";
				unset($diskspk[$row[0]]);
			}
		while (list($key, $value) = each($diskspk)) 
			{
				//echo "<p  style=\"color:gray;\"><a href=\"?folder=$key\">$key</a> <progress value=\"0\" max=\"1\" style=\"width: 500px\"></progress>0/$value</p>";
				$resultstring[$key] = "<p  style=\"color:gray;\"><a href=\"?folder=$key\">$key</a> <progress value=\"0\" max=\"1\" style=\"width: 500px\"></progress>0/$value</p>";
			}
		}
	//echo "<br><br><br>";
	
	ksort($resultstring);
	foreach ($resultstring as $key => $val) {
		echo "$val\n";
	}
	
	echo "<br><br><br>";
	//echo $rownum;
	// //done
	// $q="SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=1 AND (FOLDER) NOT IN (SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=0);";
	// $res = mysqli_query($dbc, $q);
	// if (!$res)
	// {}
	// else 
		// {
		// while ($row=mysqli_fetch_row($res))
			// {echo "<p  style=\"color:green;\"><a href=\"?folder=$row[0]\">$row[0]</a>  done!</p>";}
		// }
	// //will do
	// $q="SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=0 AND (FOLDER) NOT IN (SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=1);";
	// $res = mysqli_query($dbc, $q);
	// if (!$res)
	// {}
	// else 
		// {
		// while ($row=mysqli_fetch_row($res))
			// {echo  "<p  style=\"color:red;\"><a href=\"?folder=$row[0]\">$row[0]</a>  will do</p>";}
		// }

?>
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>
