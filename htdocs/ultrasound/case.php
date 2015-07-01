<!DOCTYPE html>
<html>
<head>
<script src="//fb.me/react-0.8.0.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="js/json2.js"></script>
<script>
function Coo(x, y){
        this.x = x;
        this.y = y;     
    }
function remove_last(s)
	{
	var points =$.parseJSON(window.sessionStorage.getItem('points'));
	points.splice(points.length-parseInt(s),parseInt(s));
	window.sessionStorage.setItem('points', JSON.stringify(points));
	top.location.reload();
	//window.location=window.location;
	//top.location.href=top.location.href
	//	upDate();
	}
function remove_five()
	{
	var points =$.parseJSON(window.sessionStorage.getItem('points'));
	points.splice(points.length-5,5);
	window.sessionStorage.setItem('points', JSON.stringify(points));
	top.location.reload();
	//window.location=window.location;\
	//top.location.href=top.location.href
	//	upDate();
	}
function remove_all()
	{
	var points =$.parseJSON(window.sessionStorage.getItem('points'));
	points=new Array();
	window.sessionStorage.setItem('points', JSON.stringify(points));
	//top.location.reload(true);
	//window.location=window.location;
	//top.location.href=top.location.href
	//upDate();
	}
function show_submit(p){
	if (p.length>=3)
		{
		document.getElementById("sendtoDB").style.display='block';}
	}


$(document).ready(function() {
	var paint;
	$('.box').mouseup(function(e){
		paint = false;
		});
	$('.box').mouseleave(function(e){
		paint = false;
		});
	$('.box').mousedown(function(e){
		if (e.button==2)
			{remove_last(20);}
		else {
			paint=true;
			var offset = $(this).offset();
			var point =new Coo(Math.round(e.clientX - offset.left),Math.round(e.clientY - offset.top));
			// if (localStorage.getItem("points")!=null) {
				// var points=JSON.parse(localStorage.getItem("points"));}
			// else 
				// {var points=new Array();}
			// points.push(point);
			// localStorage.setItem("points", JSON.stringify(points));
			if (window.sessionStorage.getItem('points')==null)
				{
				var points=new Array();;
				}
			else 
				{		
				var points =$.parseJSON(window.sessionStorage.getItem('points'));
				//document.write(Object.prototype.toString.call(points));
				}
			points.push(point);
			window.sessionStorage.setItem('points', JSON.stringify(points));
			$('#position').text(point.x+ ", " + point.y);
			$('#size').text(points.length);
			//document.write(JSON.stringify(points));
			//document.write(Object.prototype.toString.call($('xy').text('points',points)));
			//document.write(points);
			
			//draw dot !!!
			 var can = document.getElementById('canvas');
			 var ctx = can.getContext('2d');
			 var txtcoor="";
			 
			// + CsapoTG, 3 points, 2 lines			
			if (points.length == 3)
			{
				ctx.beginPath();
				ctx.strokeStyle = "red";
				ctx.lineWidth = 2;				
				ctx.moveTo(points[0].x, points[0].y);
				ctx.lineTo(points[1].x, points[1].y);
				ctx.lineTo(points[2].x, points[2].y);				
				ctx.stroke();
			}			
			// - CsapoTG, 3 points, 2 lines
			 
			 for (var i=0;i<points.length;i++)
				{
				//document.write(points[i].x+"---"+points[i].y + "<br>");
					ctx.fillStyle="red";
					ctx.fillRect(points[i].x-3,points[i].y-3,6,6);
					txtcoor+=points[i].x.toString() +" "+ points[i].y.toString()+"\n";
				}
			
			
			
			document.getElementById("coors").value = txtcoor;
			show_submit(points);
			}
		});
  $('.box').mousemove(function(e) {
	if (paint){
		var offset = $(this).offset();
		var point =new Coo(Math.round(e.clientX - offset.left),Math.round(e.clientY - offset.top));
		// if (localStorage.getItem("points")!=null) {
			// var points=JSON.parse(localStorage.getItem("points"));}
		// else 
			// {var points=new Array();}
		// points.push(point);
		// localStorage.setItem("points", JSON.stringify(points));
		if (window.sessionStorage.getItem('points')==null)
			{
			var points=new Array();;
			}
		else 
			{		
			var points =$.parseJSON(window.sessionStorage.getItem('points'));
			//document.write(Object.prototype.toString.call(points));
			}
		points.push(point);
		window.sessionStorage.setItem('points', JSON.stringify(points));
		$('#position').text(point.x+ ", " + point.y);
		$('#size').text(points.length);
		//document.write(JSON.stringify(points));
		//document.write(Object.prototype.toString.call($('xy').text('points',points)));
		//document.write(points);
		
		//draw dot !!!
		 var can = document.getElementById('canvas');
		 var ctx = can.getContext('2d');
		 var txtcoor="";		
		 
		 for (var i=0;i<points.length;i++)
			{
			//document.write(points[i].x+"---"+points[i].y + "<br>");
				ctx.fillStyle="red";
				ctx.fillRect(points[i].x-3,points[i].y-3,6,6);	
				txtcoor+=points[i].x.toString() +" "+ points[i].y.toString()+"\n";
			}
		document.getElementById("coors").value = txtcoor;
		show_submit(points);
		}
  });
  
  
});


function upDate(){
	var can = document.getElementById('canvas');
	var ctx = can.getContext('2d');
	var img = new Image();
	img.onload = function(){
		can.width = img.width;
		can.height = img.height;
		ctx.drawImage(img, 0, 0, img.width, img.height);
		var points= $.parseJSON(window.sessionStorage.getItem('points'));
	// //document.write(Object.prototype.toString.call(points));
	// //document.write('-----'+points[0].x+'-------');
	
		for (var i=0;i<points.length;i++)
		{
		//document.write(points[i].x+"---"+points[i].y + "<br>");
			ctx.fillStyle="red";
			ctx.fillRect(points[i].x-3,points[i].y-3,6,6);	
		}
		show_submit(points);

		
		$('#size').text(points.length);
	}};




</script>
<style type="text/css">
canvas.box{
cursor:url("/redcross2.cur"), auto;

}
</style>
</head>
<body>


 <?php #admin/restricted.php 
           // #####[mak.e sure you put this code before any html output]#####

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
if(isset($_POST['logout'])){
        //if the user logged out, delete any SESSION variables
	session_destroy();
	echo "<script>window.location = 'index.php'</script>";
	die();
	}
if(isset($_POST['tospeakers']) ){
	// echo '<script >', 'window.sessionStorage.clear();;', '</script>';
	echo "tospeakers";
	//header('Location:http://www.google.com');
	header('Location:folders.php');
	}
if(isset($_POST['tosessions'])){
	echo "tosessions";
	// echo '<script >', 'window.sessionStorage.clear();', '</script>';
	header('Location:sess.php');}
	
//get the value from previous session
$tablename=$_SESSION['username'];
$session=$_SESSION['sess'];
$case=sprintf( '%04d', $_SESSION['case'] );
$casenum=$_SESSION['case'];
$speaker=$_SESSION['speaker'];
$maxcase=$_SESSION['maxcase'];
$maxnum=$_SESSION['maxcase'];
$dbc = mysqli_connect('localhost','root','slulich') or die('could not connect: '. mysqli_connect_error());
//select db
mysqli_select_db($dbc, 'ultrasound_new') or die('no db connection');
$q="select * from $tablename where spk_id='$speaker' and sess_id='$session' and img_id='$casenum';";
$res = mysqli_query($dbc, $q);	
$row=mysqli_fetch_row($res);
$doneflag=0;


if (isset($_POST['reset']))
	{
	$temp=$row[0];
	$q="delete from $tablename where c_id='$temp';";
	$res = mysqli_query($dbc, $q);	
	$row=mysqli_fetch_row($res);	
	echo $row;
	echo '<script type="text/javascript">', 'remove_all();', '</script>';
	//echo '<script type="text/javascript">location.reload();</script>';

	//unset($_POST['reset']);
	}
	
	
if ($row[4]!=NULL)
	{
	$doneflag=$row[0];
	//$_SESSION['doneflag']=$row[0];
	$prepoints=explode("\n",$row[4]);
	echo '<script type="text/javascript"> var ps=new Array();';
	foreach ($prepoints as $i)
		 {
		$ii=explode(" ",$i);
		$x=$ii[0];
		$y=$ii[1];
		if ($x==NULL)
			{continue;}
		echo "var p=new Coo($x,$y);";
		echo 'ps.push(p);';
		}
		//$txtpoints=json_encode($row[5]);
	if (count($prepoints)>0)
		{echo 'window.sessionStorage.setItem(\'points\', JSON.stringify(ps));</script>';}	
	}

function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
if (isset($_POST['sendout']))	
	{
	$coors = test_input($_POST["coors"]);
	// if (isset($_POST['doneflag']))
		// {
		// $doneflag1=$_POST['doneflag'];
		// $q="update ultrasound_new.res set coors='$coors' where c_id= '$doneflag1';";
		// unset($session['doneflag']);
		// }
	// else
		// {
		$q="INSERT INTO $tablename (spk_id,sess_id,img_id,coors) VALUE ('$speaker','$session','$casenum','$coors');";
		// }
	//$q="select * from ultrasound_new.res ;";
	$res = mysqli_query($dbc, $q);	
	$row=mysqli_fetch_row($res);
	echo "<h1 style=\"color:Lime; position: absolute; bottom:0;left:0;\">PASS!$row[0]</h1>";
	//header('location:case.php');
	}	


	
if (isset($_POST['next']))
	{	
	$_SESSION['case']=1+$casenum;
	echo '<script type="text/javascript">', 'remove_all();', '</script>';
	unset($session['doneflag']);
	//echo '<script type="text/javascript">location.reload();</script>';
	//header('location:image.php');
	//echo '<script type="text/javascript">', 'window.location="image.php"', '</script>';
	}	
	
if (isset($_POST['previous']))
	{	
	$_SESSION['case']=$casenum-1;
	echo '<script type="text/javascript">', 'remove_all();', '</script>';
	unset($session['doneflag']);
	//echo '<script type="text/javascript">location.reload();</script>';
	//header('location:image.php');
	//echo '<script type="text/javascript">', 'window.location="image.php"', '</script>';
	}
?> 



 <!-- RESTRICTED PAGE HTML GOES HERE -->
 <!-- add a LOGOUT link before the form -->

<form method="post" action="#">
	<p><button id= "logout" type="submit" name="logout" value="out" style="position: absolute; top: 0; right: 0;"> log out</button> </p> 
</form>
<form method="post" action="folders.php">
	<p><button id= "tospeakers" type="submit" name="tospeakers" style="position: absolute; top: 0; right: 400px;"> to speakers</button> </p> 
	</form>
<form method="post" action="sess.php">
	<p><button id= "tosessions" type="submit" name="tosessions" style="position: absolute; top: 0; right: 200px;"> to sessions</button> </p> 
</form>

<form method="post" action="">
	<p>
		<button type="submit" name="reset" style="width: 200px; height: 60px;position: absolute; top: 400px; left: 850px;"> reset_points</button>
	</p>
</form>
<form method="post" action="image.php">
	<p>
		<button id= "next" type="submit" name="next"  style="<?php if($maxnum<=$casenum) echo 'display:none;';?> position: absolute; bottom: 50px; right: 100px; width: 200px; height: 60px;font-size:20px;"> Next</button>
	</p>
</form>	
<form method="post" action="image.php">
	<p>
		<button id= "previous" type="submit" name="previous"  style="<?php if(1==$casenum) echo 'display:none;';?> position: absolute; bottom: 50px; right: 560px; width: 200px; height: 60px;font-size:20px;"> Previous</button>
	</p> 
</form>
<form method="post" action="#">
	<textarea id="coors" name="coors" rows="5" cols="40" style="display:none"></textarea>
	
    <br><br>
	
	<input id="sendtoDB" type="submit" name="sendout" value="Submit" style="display:none;width: 200px; height: 60px; position: absolute; bottom: 50px; right: 350px;font-size:20px;"> 
</form>

 <?php

	echo "<h3>$speaker/$session/$case.jpg</h3>";
	$imagefile="/ultrasounddata/".$speaker.'/'.$session.'/'.$case.".jpg";
 ?>

 <span style="newcursor">
 <canvas id="canvas" class="box" style="width: 800px; height: 600px;" >
 </canvas ></span>
 <script>
	var can = document.getElementById('canvas');
	var ctx = can.getContext('2d');
	var img = new Image();
	img.onload = function(){
		can.width = img.width;
		can.height = img.height;
		ctx.drawImage(img, 0, 0, img.width, img.height);
		var points= $.parseJSON(window.sessionStorage.getItem('points'));
		//document.write(Object.prototype.toString.call(points));
		//document.write('-----'+points[0].x+'-------');
		for (var i=0;i<points.length;i++)
		{
		//document.write(points[i].x+"---"+points[i].y + "<br>");
			ctx.fillStyle="red";
			ctx.fillRect(points[i].x-3,points[i].y-3,6,6);	
		}
		
		// + CsapoTG, 3 points, 2 lines			
		if (points.length == 3)
		{
			ctx.beginPath();
			ctx.strokeStyle = "red";
			ctx.lineWidth = 2;				
			ctx.moveTo(points[0].x, points[0].y);
			ctx.lineTo(points[1].x, points[1].y);
			ctx.lineTo(points[2].x, points[2].y);				
			ctx.stroke();
		}			
		// - CsapoTG, 3 points, 2 lines
		
		show_submit(points);		
		$('#size').text(points.length);
	}
	img.src = <?php echo json_encode($imagefile); ?>;

	//drwa data point
	
	//document.write("points above!!!");
	
	
</script> 
	<p><button type="button" name="back1" onclick="remove_last(10)" style="width: 200px; height: 60px;position: absolute; top: 100px; left: 850px;"> back_10</a></button> </p> 
	<p><button type="button" name="back5" onclick="remove_last(20)" style="width: 200px; height: 60px;position: absolute; top: 250px; left: 850px;"> back_20</a></button> </p> 
	 
	
	
	
  <p id="position"></p>
  <br>
  <p id="size"></p><br>
	



<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>