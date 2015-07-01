<!DOCTYPE html>
<html>
<head>
<script>
function displayResult(s)
{
document.getElementById("result").value=s
}

function show(s) {
	if(document.getElementById(s).style.display=='none') {
		document.getElementById(s).style.display='block';
	}
	return false;
}
function hide(s) {
	if(document.getElementById(s).style.display=='block') {
		document.getElementById(s).style.display='none';
	}
	return false;
}   
function curchange(a)
	{
	document.getElementById("cur").innerHTML=a;
	}
</script>



<style>
.next {
color:blue;
}
.error {color: #FF0000;}
</style>
</head>
<body>


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
	header('location:index.php');}
if(isset($_GET["tores"]) && ($_GET["tores"]=="yes")){
	header('location:restricted.php');}


 //<!-- RESTRICTED PAGE HTML GOES HERE -->
 //<!-- add a LOGOUT link before the form -->
	//echo "<p><button type=\"submit\"  style=\"position: absolute; top: 0; right: 0;\"> <a href=\"?log=out\">log out</a></button> </p> ";
 // echo "<p><button type=\"submit\"  style=\"position: absolute; top: 0; right: 400px;\"> <a href=\"?tores=yes\">database</a></button> </p> ";
  //echo "<p> <a href=\"?log=out\">log out</a> </p> ";
  //echo "<p> <a href=\"?tores=yes\">database</a> </p> ";
?>
<form method="get" action="#">
  <p><button type="submit" name="log" value="out" style="position: absolute; top: 0; right: 0;"> log out</a></button> </p> 
    <p><button type="submit" name="tores" value="yes" style="position: absolute; top: 0; right: 400px;"> database</a></button> </p> 
 </form>
 
 <?php
	if(isset($_GET['page']) ){
		$_SESSION['page']=$_GET['page'];
		header('location:page.php');
	}
	
	
	$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

	//select db
	mysqli_select_db($dbc, 'wuuclakids') or die('no db connection');
	$tablename=$_SESSION['username'];
	$folder=$_SESSION['folder'];
	
	
	$q="SELECT COUNT(*) FROM $tablename WHERE FOLDER='$folder' and VIEWED=1;";		
	$res = mysqli_query($dbc, $q);
	$row=mysqli_fetch_row($res);
	$q="SELECT COUNT(*) FROM $tablename WHERE FOLDER='$folder'";		
	$res = mysqli_query($dbc, $q);
	$row1=mysqli_fetch_row($res);
	$temp=$row[0]/$row1[0];
	echo "<h3 class=\"next\"  >Current folder status: <progress value=\"$temp\" max=\"1\" style=\"width: 500px\"></progress> <span id=\"cur\">$row[0]</span>/ $row1[0]</h3>";
	
	
	$q="SELECT FILENAME,VIEWED FROM $tablename WHERE FOLDER='$folder' ";	
	$res = mysqli_query($dbc, $q);
	
	if (!$res)
	{echo "got nothing";}
	else 
		{$folder_files=array();
		while ($row=mysqli_fetch_row($res))
			{
			if ($row[1]=='1')
			{echo "<p  style=\"color:green;\"><a href=\"?page=$row[0]\">$row[0]</a>  $row[1]</p>";
			}
			else 
				{echo "<p  style=\"color:red;\"><a href=\"?page=$row[0]\">$row[0]</a>  $row[1]</p>";
				array_push($folder_files,$row[0]);}
			}
		}
	$_SESSION['folder_files']=$folder_files;
?>
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>