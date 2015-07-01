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
	header('location:index.php');}
if(isset($_GET['tospeakers']) && ($_GET['tospeakers']=='1')){
	header('location:folders.php');}
//end log out

?> 
 <!-- RESTRICTED PAGE HTML GOES HERE -->
 <!-- add a LOGOUT link before the form -->
<form method="get" action="#">
  <p><button type="submit" name="log" value="out" style="position: absolute; top: 0; right: 0;"> log out</a></button> </p> 
  <p><button type="submit" name="tospeakers" value="1" style="position: absolute; top: 0; right: 400px;"> to speakers</a></button> </p> 
 </form>
 

<?php
	if(isset($_GET['sess']) ){
		echo $_GET['sess'];
		$_SESSION['sess']=$_GET['sess'];
		header('location:sess.php');
	}
	$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

	//select db
	mysqli_select_db($dbc, 'ultrasound') or die('no db connection');
	$tablename=$_SESSION['username'];
	$spkid=$_SESSION['speaker'];
	//$tablename=strtoupper($tablename);
	
	
	
	
	
	//doing 
	$q="SELECT sess_id, COUNT(sess_id) FROM res where user_name='$tablename' and spk_id='$spkid' and coors is not null group by sess_id;" ;
	//echo $q;
	//$q="SELECT DISTINCT files FROM $tablename WHERE VIEWED=1; ";
	//step3: run the query and store result	
	$res = mysqli_query($dbc, $q);
	$rownum=0;
	$session2casenum=array_filter(glob('E:/ultrasoundimage/'.$_SESSION['speaker'].'/*'), 'is_dir');
	$disksess=diskto($session2casenum);
	echo "<h3>$spkid</h3>";
	if (!$res)
	{}
	else 
		{
		while ($row=mysqli_fetch_row($res))
			{
			$rownum+=1;
			$temp=$row[1]/$disksess[$row[0]];			
			echo "<p  style=\"color:gray;\"><a href=\"?sess=$row[0]\">$row[0]</a> <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress>$row[1]/{$disksess[$row[0]]}</p>";
			unset($disksess[$row[0]]);
			}
		while (list($key, $value) = each($disksess)) 
			{echo "<p  style=\"color:gray;\"><a href=\"?sess=$key\">$key</a> <progress value=\"0\" max=\"1\" style=\"width: 500px\"></progress>0/$value</p>";
			}
		}
	echo "<br><br><br>";


?>
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>