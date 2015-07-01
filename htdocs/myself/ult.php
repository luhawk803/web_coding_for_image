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
<h1 style="color: blue">Extraction of the tongue surface from 4D ultrasound</h1>
<h5>Code design& implement: Hao Lu &nbsp &nbsp  Lab Director:Dr.Steven Lulich </h5>
<h3>Background</h3>
This project is served for 4D ultrasound tongue surface data. Traditional ultrasound machine is only used for 3D data capture. <a href="http://www.youtube.com/watch?v=N9uX7b-ajdM">[2D vs 3D,4D]</a> With this machine, we can capture the 3D structure of the tongue  during time stamp. In speech research 3D tongue  surface is a very challenge topic, cause the tongue surface is a complex system for which we typically have incomplete information. 


<h3>Ultrasound</h3>
<p>For ultrasound we use the  <a href="http://www.healthcare.philips.com/main/products/ultrasound/systems/epiq7/"> PHILIPS EPIQ 7 Ultrasound system<a>.  The raw data we captured is looks like following:</p>
<video width="600" height="400" controls autoplay>
  <source src="ult/orignal.ogg" type="video/ogg">
</video>
<p>The video the ultrasound image of tongue during speaking Vowel [i],[a],[u]</p>
<h3>Algorithm </h3>

<ol>
	<li>Image smoothed by <a href="http://en.wikipedia.org/wiki/Gaussian_blur">Gaussian Convolution</a></li>
	<li>2-D first <a href="http://en.wikipedia.org/wiki/Roberts_cross">derivative operator</a>. </li>
	<li>Canny edge detection on the effective region of image data.</li>
	<li>Based on the known information to fit the edge detection result.</li>
	<li>Dynamic program to get the information base on the N frame tongue surface result to predict the N+1 frame tongue surface.  </li>
</ol>

<h3>Result</h3>
<p>Extract the 3D tongue surface fro each frame  </p>
<video width="600" height="400" controls autoplay>
  <source src="ult/mid2d.ogg" type="video/ogg">
</video>
<p>Extract the 2D mid-sagittal contour of the tongue for each frame. </p>
<video width="600" height="400" controls autoplay>
  <source src="ult/3d.ogg" type="video/ogg">
</video>


<p>3D mid-sagittal contour of the tongue</p>
<img src="ult/mid-sag3d.png" width="400" height="300">
<p>Vowel [a]  tongue surface</p>
<img src="ult/a.png" width="400" height="300">
<p>Vowel [i]  tongue surface</p>
<img src="ult/i.png" width="400" height="400">
<p>Vowel [u]  tongue surface</p>
<img src="ult/u.png" width="400" height="400">
</body>
</html>