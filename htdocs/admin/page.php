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
position:absolute;
left:0px;
top:50px;}
.error {color: #FF0000;}
</style>
</head>
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
 //connect the data base
$dbc = mysqli_connect('localhost','root','slulich') or 
	   die('could not connect: '. mysqli_connect_error());
//get the current the current folder file statues
mysqli_select_db($dbc, 'wuuclakids') or die('no db connection');
$folder=$_SESSION['folder'];
$tablename=$_SESSION['username'];
$q="SELECT COUNT(*) FROM $tablename WHERE FOLDER='$folder' and VIEWED=1;";		
$res = mysqli_query($dbc, $q);
$row=mysqli_fetch_row($res);
$q="SELECT COUNT(*) FROM $tablename WHERE FOLDER='$folder'";		
$res = mysqli_query($dbc, $q);
$row1=mysqli_fetch_row($res);
$temp=$row[0]/$row1[0];
echo "<h3 class=\"next\" >Current folder status: <progress value=\"$temp\" max=\"1\" style=\"width: 150px\"></progress> <span id=\"cur\">$row[0]</span>/ $row1[0]</h3>";
echo "<script>curchange($row[0])</script>";



if(isset($_POST['logout'])){
        //if the user logged out, delete any SESSION variables
	session_destroy();
	header('location:index.php');
	}
if(isset($_POST['todatabase']) ){
	header('location:restricted.php');
	}
if(isset($_POST['tofolder'])){
	header('location:folderinfo.php');}

//deal with the input make sure the input is right.
	$micgrade= $micwhy=$micother=$accgrade= $accwhy=$accother=NULL;
	$micgradeErr = $micwhyErr = $micotherErr = "";
	$accgradeErr = $accwhyErr = $accotherErr = "";
	$flag=0;
	//mic fill test
	//if ($_SERVER["REQUEST_METHOD"] == "POST")
	if (isset($_POST["handon"]))
		{
		
		if (empty($_POST["micgrade"]))
			{$flag=1;$micgradeErr = "micgrade is required";}
		else
			{
			$micgrade = test_input($_POST["micgrade"]);
			if ($micgrade=='A')
				{}
			elseif ($micgrade!='A'&&empty($_POST["micwhy"]))
				{$flag=1;$micwhyErr = "micwhy is required";}
			else 
				{
				$micwhy=test_input($_POST["micwhy"]);
				if ($micwhy!='O')
					{}
				elseif ($micwhy=='O'&& empty($_POST["micother"]))
					{$flag=1;$micotherErr="micOther text box need fill";}
				else 
					{$micother=test_input($_POST["micother"]);}
				}			
			}		
		
	//acc fill test
		if (empty($_POST["accgrade"]) && $micgrade!='F')
			{$flag=1;$accgradeErr = "accgrade is required";}
		elseif(empty($_POST["accgrade"]) && $micgrade=='F')
			{}
		else
			{
			$accgrade = test_input($_POST["accgrade"]);
			if ($accgrade=='A')
				{}
			elseif ($accgrade!='A'&&empty($_POST["accwhy"]))
				{$flag=1;$accwhyErr = "ACCwhy is required";}
			else 
				{
				$accwhy=test_input($_POST["accwhy"]);
				if ($accwhy!='O')
					{}
				elseif ($accwhy=='O'&& empty($_POST["accother"]))
					{$flag=1;$accotherErr="ACCOther text box need fill";}
				else 
					{$accother=test_input($_POST["accother"]);}
				}			
			}
		if ($flag==1)
			{
			echo "<h1 style=\"color:red;position: absolute; bottom:0;left:0;\">Something wrong!</h1>";
			}
		else{
			$dbc = mysqli_connect('localhost','root','slulich') or 
			die('could not connect: '. mysqli_connect_error());
			//select db
			mysqli_select_db($dbc, 'wuuclakids') or die('no db connection');
			$tablename=$_SESSION['username'];
			$filename=$_SESSION['page'];
			if (empty($micwhy))
				{
				$q="UPDATE $tablename SET VIEWED=1,MICRANK='$micgrade',MICREASON=NULL, MICCOMM=NULL WHERE FILENAME='$filename';";
				}
			elseif (empty($micother))
				{$q="UPDATE $tablename SET VIEWED=1,MICRANK='$micgrade', MICREASON='$micwhy',MICCOMM=NULL  WHERE FILENAME='$filename';";}
			else 
				{$q="UPDATE $tablename SET VIEWED=1,MICRANK='$micgrade', MICREASON='$micwhy', MICCOMM='$micother' WHERE FILENAME='$filename';";}
			$res = mysqli_query($dbc, $q);	
			//echo "<br>",$q;
			if (empty($accwhy)&& $micgrade!='F')
				{
				$q="UPDATE $tablename SET ACCRANK='$accgrade', ACCREASON=NULL, ACCCOMM=NULL WHERE FILENAME='$filename';";
				}
			elseif (empty($accwhy)&& $micgrade=='F')
				{
				$q="UPDATE $tablename SET ACCRANK=NULL, ACCREASON=NULL, ACCCOMM=NULL WHERE FILENAME='$filename';";
				}
			elseif (empty($accother))
				{		
				$q="UPDATE $tablename SET ACCRANK='$accgrade', ACCREASON='$accwhy',ACCCOMM=NULL WHERE FILENAME='$filename';";
				}
			else
				{
				$q="UPDATE $tablename SET ACCRANK='$accgrade', ACCREASON='$accwhy', ACCCOMM='$accother' WHERE FILENAME='$filename';";
				}	
			//echo "<br>",$q;
			$res = mysqli_query($dbc, $q);			
			echo "<h1 style=\"color:Lime; position: absolute; bottom:0;left:0;\">PASS!</h1>";
			}	
		$q="SELECT COUNT(*) FROM $tablename WHERE FOLDER='$folder' and VIEWED=1;";		
		$res = mysqli_query($dbc, $q);
		$row=mysqli_fetch_row($res);
		echo "<script>curchange($row[0])</script>";	
		}
	
	//next
if (isset($_POST["next"]))
	{
	$filename=$_SESSION['page'];
	$q="SELECT FILENAME FROM $tablename WHERE FOLDER='$folder' and VIEWED=0;";
	
	$res = mysqli_query($dbc, $q);
	$row=mysqli_fetch_row($res);
	if (empty($row))
		{
		echo "<h1 style=\"color: red;\">You have done this folder, go to database.</h1>";
		}
	else
		{			
		$_SESSION['page']=$row[0];
		header('location:page.php');
		}
	}
	
	//echo $_SERVER["REQUEST_METHOD"];
	 function test_input($data)
		{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
		}
?>



 <!-- RESTRICTED PAGE HTML GOES HERE -->
 <!-- add a LOGOUT link before the form -->
<form method="post" action="#">
	
	<p><button type="submit" name="logout" style="position: absolute; top: 0; right: 0;"> log out</a></button> </p> 
  
	<p><button type="submit" name="todatabase" style="position: absolute; top: 0; right: 400px;"> database</a></button> </p> 
    
	<p><button type="submit" name="tofolder" style="position: absolute; top: 0; right: 350px;"> folder</a></button> </p> 
	
	<p><button type="submit" name="handon" style="position: absolute; bottom: 100px;right:500px"> Submit</a></button> </p> 
	
	<p><button type="submit" name="next" style="position: absolute; bottom: 100px;right:300px"> next</a></button> </p> 
	
	
	<div style="position: absolute; top: 250px; left: 300px";>
		<h3 align="center">Grade<span class="error">* <?php echo $micgradeErr;?></span></h3>
		
		<input type="radio" name="micgrade" value="A" onclick="hide('why1');hide('other1');show('accG')" <?php if (isset($micgrade) && $micgrade=="A") echo "checked;";?> />A<br>
		<input type="radio" name="micgrade" value="B" onclick="show('why1');show('accG')" <?php if (isset($micgrade) && $micgrade=="B") echo "checked;";?> />B<br>
		<input type="radio" name="micgrade" value="C" onclick="show('why1');show('accG')" <?php if (isset($micgrade) && $micgrade=="C") echo "checked;";?>/>C<br>
		<input type="radio" name="micgrade" value="F" onclick="show('why1');hide('accG')"  <?php if (isset($micgrade) && $micgrade=="F") echo "checked;";?>/>F<br>
	
		<br>
	</div>
	<div id="why1"  style="display:none;position: absolute; top: 400px; left: 300px">
		<h3 align="center">Why<span class="error">*<?php echo $micwhyErr;?></span></h3>
		
		<input type="radio" name="micwhy" value="N"  onclick="hide('other1')" />Noise<br>
		<input type="radio" name="micwhy" value="M"  onclick="hide('other1')" />Mispronunciation<br>
		<input type="radio" name="micwhy" value="D"  onclick="hide('other1')" />Disfluency      <br>
		<input type="radio" name="micwhy" value="O"  onclick="show('other1')" />Other       <br><br><br><br><br><br><br>
	</div>
	<div id="other1" style="display:none;position: absolute; top: 550px; left:200px">
		<p><b style=>Other:</b><font style="color: blue"> (less than 100 characters)</font><span class="error">* <?php echo $micotherErr;?></span></p>
		<textarea name="micother" rows="5" cols="30" maxlength="100" ></textarea>
	</div>
		

	<!--- ACC side format -->
	<div style="position: absolute; top: 250px; right: 300px";>
		<div id="accG" style="display:none;"> 
		<h3 align="center">Grade<span class="error">*<?php echo $accgradeErr;?></span></h3>
		<input type="radio" name="accgrade" value="A" onclick="hide('why2');hide('other2')" />A<br>
		<input type="radio" name="accgrade" value="B" onclick="show('why2')" />B<br>
		<input type="radio" name="accgrade" value="C" onclick="show('why2')"/>C<br>
		<input type="radio" name="accgrade" value="F" onclick="show('why2')" />F<br>
		</div>
		<br>
	</div>
		<div id="why2"  style="display:none;position: absolute; top: 400px; right: 250px">
		<h3 align="center">Why<span class="error">*<?php echo $accwhyErr;?></span></h3>
		<input type="radio" name="accwhy" value="N"  onclick="hide('other2')" />Noise<br>
		<!--input type="radio" name="accwhy" value="M"  onclick="hide('other2')" />Mispronunciation<br>
		<input type="radio" name="accwhy" value="D"  onclick="hide('other2')" />Distracted      <br-->
		<input type="radio" name="accwhy" value="O"  onclick="show('other2')" />Other       <br><br><br><br><br><br><br>
	</div>
	<div id="other2" style="display:none;position: absolute; top: 550px; right:200px">
		<p><b style=>Other:</b><font style="color: blue"> (less than 100 characters)</font><span class="error">*<?php echo $accotherErr;?></span></p>
		<textarea name="accother"  rows="5" cols="30" maxlength="100" ></textarea>
	</div>
	
</form>

<?php
//place the mic and acc audio and get information from the data base:
	$restfile=$_SESSION['folder_files'];
	
	$tablename=$_SESSION['username'];
	$folder=$_SESSION['folder'];
	$filename=$_SESSION['page'];
	$q="SELECT * FROM $tablename WHERE FILENAME='$filename';";

	$res = mysqli_query($dbc, $q);
	$row=mysqli_fetch_row($res);
	echo "<h1 align=\"center\" style=\" color: blue\"> $row[2]</h1>";
	echo "<div style=\"position: absolute; top: 100px; left: 200px;\"><h2 align=\"center\">MIC</h2><br><audio controls > <source src=\"/audiodata/$folder/$filename", "mic.wav\"","type=\"audio/webm\"></audio></div>";
	echo "<div style=\"position: absolute; top: 100px; right: 200px;\"><h2 align=\"center\">ACC</h2><br><audio controls > <source src=\"/audiodata/$folder/$filename", "acc.wav\"","type=\"audio/webm\"></audio></div>";
	//echo "<audio controls> <source src=\"../../../../../NSF WashU-UCLA Kids SGR Database/$folder/$filename", "mic.wav\" type=\"audio/webm\"></audio>";
?>



<!--- MIC side format -->
<?phpif (isset($_POST["handon"])){echo "asdfasdfasdfasdfasdfasdfasdf;";}?>

<?php
echo "<h2>database result:</h2>";
echo "<br>";
echo "MG:",$row[4];
echo "<br>";
echo "MW:",$row[5];
echo "<br>";
echo "MC:",$row[6];
echo "<br>";
echo "AG:",$row[7];
echo "<br>";
echo "AW:",$row[8];
echo "<br>";
echo "AC:",$row[9];?>
	
<?php
echo "<h2>Your Input:</h2>";
 //echo $_SERVER["REQUEST_METHOD"];
 echo "<br>";
echo $micgrade;
echo "<br>";
echo $micwhy;
echo "<br>";
echo $micother;
echo "<br>";
echo $accgrade;
echo "<br>";
echo $accwhy;
echo "<br>";
echo $accother;?>
<span class="error"><?php echo $micgradeErr;?></span><br>
<span class="error"> <?php echo $micwhyErr;?></span><br>
<span class="error"> <?php echo $micotherErr;?></span><br>
<span class="error"> <?php echo $accgradeErr;?></span><br>
<span class="error"> <?php echo $accwhyErr;?></span><br>
<span class="error"> <?php echo $accotherErr;?></span><br>
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>

</body>
</html>



