<!-- LOGIN FORM in: admin/index.php -->
<h1>Speech and hearing Ultrasound experiment data coding program</h1>
<form method="post" action="#">
    <p><label for="u_name">username:</label></p>
    <p><input type="text" name="u_name" value=""></p>
    
    <p><label for="u_pass">password:</label></p>
    <p><input type="password" name="u_pass" value=""></p>
    
    <p><button type="submit" name="go">log me in</button></p>
</form>
<!-- A paragraph to display eventual errors -->
<p><strong><?php if(isset($error)){echo $error;}  ?></strong></p> 
<?php    
$file = fopen("ip.txt", "a");   
$timeop=date("Y-m-d H:i:s");
fwrite($file, $_SERVER["REMOTE_ADDR"]."\t".$timeop."\n"); 
//fputs($file, "$_SERVER["REMOTE_ADDR"] connected $numday $month $year at $hour h $minutes\n");   
fclose($dest);   
?>  
<?php #admin/index.php 
           #####[make sure you put this code before any html output]#####
 //echo $_SERVER["REQUEST_METHOD"];
//connect to server
$dbc = mysqli_connect('localhost','root','slulich') or 
           die('could not connect: '. mysqli_connect_error());

//select db
mysqli_select_db($dbc, 'ultrasound') or die('no db connection');
//check if the login form has been submitted
if(isset($_POST["go"])){
	#####form submitted, check data...#####
	
        //step 1a: sanitise and store data into vars (storing encrypted password)
	$usr = mysqli_real_escape_string($dbc, htmlentities($_POST['u_name']));
	$psw = SHA1($_POST['u_pass']) ; //using SHA1() to encrypt passwords  
      //echo $_SERVER["REQUEST_METHOD"];
        //step2: create query to check if username and password match
	$q = "SELECT * FROM users WHERE username='$usr' AND password='$psw'  ";
	
	//step3: run the query and store result
	$res = mysqli_query($dbc, $q);
	//make sure we have a positive result
	if(mysqli_num_rows($res) == 1){
		#########  LOGGING IN  ##########
		//starting a session  
                session_start();

                //creating a log SESSION VARIABLE that will persist through pages   
		$_SESSION['log'] = 'in';
		$_SESSION['username'] = $usr;
		echo "right";
		//redirecting to restricted page
		header('location:folders.php');
	} else {
		echo "wrong";
                //create an error message   
		$error = 'Wrong details. Please try again';	
	}
}//end isset go
?> 
<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
<!-- HTML FORM GOES HERE -->