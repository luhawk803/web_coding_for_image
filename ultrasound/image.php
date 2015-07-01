<!DOCTYPE html>
<html>
<body>


 <?php #admin/restricted.php 
           // #####[mak.e sure you put this code before any html output]#####

// //starting the session
 session_start();
 echo "hi ",$_SESSION['username'];
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
 

<?php
	if(isset($_GET['folder']) ){
		echo $_GET['folder'];
		$_SESSION['folder']=$_GET['folder'];
		header('location:folderinfo.php');
	}
	

	


	$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

	//select db
	mysqli_select_db($dbc, 'wuuclakids') or die('no db connection');
	$tablename=$_SESSION['username'];
	//$tablename=strtoupper($tablename);
	
	
	
	
	
	//doing 
	$q="SELECT FOLDER , COUNT(FOLDER), SUM(VIEWED) FROM $tablename group by FOLDER;";
	//$q="SELECT DISTINCT FOLDER FROM $tablename WHERE VIEWED=1; ";
	//step3: run the query and store result	
	$res = mysqli_query($dbc, $q);
	$rownum=0;
	if (!$res)
	{}
	else 
		{
		while ($row=mysqli_fetch_row($res))
			{
			$rownum+=1;
			$temp=$row[2]/$row[1];
			echo "<p  style=\"color:gray;\"><a href=\"?folder=$row[0]\">$row[0]</a> <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress>$row[2]/$row[1]</p>";}
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