<!DOCTORATE html5>
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
<h1 style="color: blue">Hands, head detection in first person view video</h1>
<h5>Hao Lu</h5><a href="hands/finalluha.pdf" >[pdf download]</a>
<h3>Background</h3>
<p>Advances in camera miniaturization and mobile computing made the camera feasible to capture and process photos and videos from the camera worn on a person's body. So that made egocentric video is one of the hot computer vision and image processing topic recently. Current, there are a lot of products have this feature, such as Google glasses, Go-pro. So with this kind of equipment psychology research can have difference cue and simulate the human visual information to analyses those problem. Such like for the children word learning study or other human behavior study. </p>
<img border="0" src="hands/gopro.jpg" width="200" height="160">
<img border="0" src="hands/googleg.jpg" width="200" height="160">
<img border="0" src="hands/psylab.jpg" width="200" height="160">
<h3>Experiment Environment</h3>
<p>The experiment is record about 120 second video records by child head mounted camera. During the experiment, parent and child seat on two sides of the table, they both are wearing head mounted cameras, and motion sensor in the hand, the device, looks like a glove. Child plays the 3 toys all the time. Parent is trying to teach the child about 3 toys name. </p>
<img src="hands/env1.jpg" width="200" height="160">
<img src="hands/env2.jpg" width="200" height="160">
<img src="hands/env3.jpg" width="200" height="160">
<img src="hands/env4.jpg" width="200" height="160">
<h3>Basic ideal</h3>
<ol>
	<li>With high precision skin detection as seed, use graph cut generate the skin blobs for each frames.</li>
	<li>Based on the frame optical flow information give every skin blobs optical flow features.</li>
	<li>For each blob, based on the blobs distance, optical flow distance, KNN decide whether merge the blobs.</li>
	<li>Based on the blobs sequential info, generate the tube </li>
	<li>Tube filter, for the tube which length is no longer enough, filter it. </li>
	<li>Tube classify based on the common sense.</li>
</ol>
<img src="hands/ideal.jpg" width="600" height="200">
<h3>Get skin blob</h3>
<p>This project is based on the premise that skin colors form a small and unique subset of the RGB color space, which makes it easier to solve this specific case of segmentation.</p>
<img src="hands/skin1.jpg" width="200" height="160">
<img src="hands/skin2.jpg" width="200" height="160">
<img src="hands/skin3.jpg" width="200" height="160">
<h3>Get optical flow</h3>
<p>Optical flow is the approximated motion vector at each pixel location. It can tell us about the relative distances of objects, as closer moving objects will have more apparent motion than moving objects that are further away, given equal speed. </p>
<video width="320" height="240" controls autoplay>
  <source src="hands/cam.ogg" type="video/ogg">
</video>
<h3>Merge close blobs</h3>
<p>we used KNN algorithm to classify the blobs which belong to one of the hands or head. </p>
<img src="hands/merge1.png" width="200" height="160">
<img src="hands/merge2.png" width="200" height="160">
<img src="hands/merge3.png" width="200" height="160">
<h3>Generate the tube</h3>
<p>In this step we solve the problem that we connect sequential frames' 2-D blobs based on the blobs location information.   The reason is because for hand or for face blobs, those blobs move slowly  if the frame rate is large that 30 Hz. Usually for same hand blobs, from one frame to next frame it will not move a lot and the hand optical flow information also in some close range.</p> 
<img src="hands/tube.jpg" width="500" height="400">
<h3>Tube categorize </h3>
<p>We based on the common sense to give the tubes definition. The common sense means, for other person's head will always in the top center of the view, other person's hand also locate at the right and left of the view, between the head and self hands. The view should be looks like this.</p>
<img src="hands/commen.png" width="500" height="400">
<h3>Final result</h3>
<p>Finally I got result of the head and hands location, with each blob center have the categorize colour.  </p>
<video width="500" height="400" controls autoplay>
  <source src="hands/tuberes.ogg" type="video/ogg">
</video>

</body>
</html>