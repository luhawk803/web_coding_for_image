<!DOCTYPE html>
<html>
<script>

sessionStorage.clear();

</script>
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
function getjpgnum($files)
	{
	$res;
	foreach ( $files as $ff)
		{
		$res[]=(int)(explode(".",end(explode("/", $ff)))[0]);		
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
if(isset($_GET['tosess']) && ($_GET['tosess']=='1')){
	header('location:files.php');}
?> 
 <!-- RESTRICTED PAGE HTML GOES HERE -->
 <!-- add a LOGOUT link before the form -->
<form method="get" action="#">
  <p><button type="submit" name="log" value="out" style="position: absolute; top: 0; right: 0;"> log out</a></button> </p> 
  <p><button type="submit" name="tospeakers" value="1" style="position: absolute; top: 0; right: 400px;"> to speakers</a></button> </p> 
  <p><button type="submit" name="tosess" value="1" style="position: absolute; top: 0; right: 200px;"> to sessions</a></button> </p> 
 </form>
 

<?php
	if(isset($_GET['case']) ){
		//echo $_GET['case'];
		$_SESSION['case']=$_GET['case'];
		header('location:case.php');
	}
	$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

	//select db
	mysqli_select_db($dbc, 'ultrasound') or die('no db connection');
	$tablename=$_SESSION['username'];
	$spkid=$_SESSION['speaker'];
	$sessid=$_SESSION['sess'];
	//$tablename=strtoupper($tablename);
	
	
	
	
	
	//doing 
	$q="SELECT img_id FROM res where user_name='$tablename' and spk_id='$spkid' and sess_id='$sessid' and coors is not null;" ;
	//echo $q;
	//$q="SELECT DISTINCT files FROM $tablename WHERE VIEWED=1; ";
	//step3: run the query and store result	
	$res = mysqli_query($dbc, $q);
	$rownum=0;
	$jpgfiles=array_filter(glob('E:/ultrasoundimage/'.$_SESSION['speaker'].'/'.$sessid.'/*.jpg'));
	$disksess=getjpgnum($jpgfiles);
	echo "<h3>$spkid/$sessid</h3>";
	if (!$res)
	{}
	else 
		{echo "<p>";
		$had_jpgs;
		while ($row=mysqli_fetch_row($res))
			{	
			$had_jpgs[]=((int) $row[0]);
			//echo "<p  style=\"color:gray;\"><a href=\"?sess=$row[0]\">$row[0]</a> <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress>$row[1]/{$disksess[$row[0]]}</p>";
			//unset($disksess[$row[0]]);
			}
		foreach ( $disksess as $ii) 
			{
			if (in_array($ii,$had_jpgs))
				{$flag='red';}
			else 
				{$flag='green';}			
			$temp=str_pad($ii, 4, '0', STR_PAD_LEFT);
			echo "<a href=\"?case=$ii\" ><button><p style=\"color:$flag;\">$temp</p></button></a> &nbsp&nbsp&nbsp";
			}
		$_SESSION['maxcase']=end($disksess);
		}
	echo "</p><br><br><br>";


?>
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>