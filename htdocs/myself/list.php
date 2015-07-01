<!DOCTYPE html5>
<html>
<head>

</head>
<body>
<?php    
$file = fopen("ip.txt", "a");   
$timeop=date("Y-m-d H:i:s");
fwrite($file, $_SERVER["REMOTE_ADDR"]."\t".$timeop."\n"); 
//fputs($file, "$_SERVER["REMOTE_ADDR"] connected $numday $month $year at $hour h $minutes\n");   
fclose($dest);   
?>  
 <?php #admin/restricted.php 
           // #####[make sure you put this code before any html output]#####

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
 <p><button type="submit" name="go" style="position: absolute; top: 0; right: 0;"> <a href="?log=out">log out</a></button> </p> 
<h1 style="color: blue">Hao Lu's Projects:</h1>
<a href="hands.php"><h3>Hands, head detection in first person view video</h3></a>
<a href="API.php"><h3>Multisensory data synchronization API</h3></a>
<a href="experiment.php"><h3>Speech and hearing experiment data coding program</h3></a>
<a href="ult.php"><h3>Extraction of the tongue surface from 4D ultrasound</h3></a>
<br><br>
<a href="cv.pdf"><h5>Hao CV</h5></a>
</body>


</html>
