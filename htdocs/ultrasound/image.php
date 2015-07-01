<!DOCTYPE html>
<html>
<body>


 <?php #admin/restricted.php 
           // #####[mak.e sure you put this code before any html output]#####

        //then redirect to login page
	echo "000000000000";
	
	session_start();

	foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>";
	$case=$_SESSION['case'];
	
	if (isset($_POST['next']))
	{	
	
		echo "set the next";
		if ($case < $_SESSION)
			{$_SESSION['case'] = $case+1;
			echo '<script type="text/javascript">', 'remove_all();', '</script>';
			}
		
	}else{echo "NOT SET THE next";}
	
		if (isset($_POST['previous']))
	{	if ($case > 1)
		{$_SESSION['case'] = $case-1;
		echo '<script type="text/javascript">', 'remove_all();', '</script>';
		}
		echo "set the previous";
		
	}else{echo "NOT SET THE previous";}
	
	//echo '<script type="text/javascript">', 'window.location="case.php"', '</script>';
	header('location:case.php');
	//exit();
//}//end log out

?> 

<p style="color: gray; position: fixed; bottom:10px;right:10px;">Design and created by Hao Lu, if you have any problems contact luha@indiana.edu</p>
</body>
</html>