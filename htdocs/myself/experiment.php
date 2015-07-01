<!DOCTYPE html5>
<html>
<head>

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
	header('location:index.php');
}//end log out

?> 
 <p><button type="submit" name="go" style="position: absolute; top: 0; right: 0;"> <a href="?log=out">log out</a></button> </p> 
<h1 style="color: blue">Speech and hearing experiment data coding program</h1>
<h5>Hao Lu</h5>
<h3>Background</h3>
<p>After speech and hearing experiment collection, we have about 30,000 sentences, 30,000 wav sound signal files, and 30,000 accelerator signal files. So in order to speed up research assistance coding job, I impalement this web based program. For each sentences, there is a microphone sound signal file record during one of the kids reading the sentences, and a accelerate signal file record the kids' vocal folds acceleration on top of the skin.</p><br>
<p>So the RA's job is hearing the sound wave file give the grade of the file(A: good, B:fare, C:can hearing,F:fail). If the it is not A, then have to give the reason why give NOT A option, could be (N:noise,M:Mispronunciation, D:distracted, O:other), when choose OTHER have to give the more detail reason.</p>

<h3>Requment</h3>
<ol>
	<li>Only functional working on a few of local compuers.</li>
	<li>Rewrite able.</li>
	<li>Give the online statisticl  of the database</li>
</ol>
<h3>Tech</h3>
<ol>
	<li>Apache2 host web.</li>
	<li>PHP for database communicationn, and web support.</li>
	<li>HTML and JS function level support.</li>
	<li>Python script to generat the user table, and register user.</li>
</ol>
<h3>Pros</h3>
<ol>
	<li>Save a lot of the RA coding time, and coding cost</li>
	<li>For long running, this program with a little modification can change to a Lab experiment and subject management program</li>
</ol>
<h3>Cons</h3>
<ol>
	<li>Security issue, login security protection </li>
	<li>User interface can be improve a lot.</li>
</ol>
</body>
</html>