<?php
//deal with the input make sure the input is right.
	$micgrade= $micwhy=$micother=$accgrade= $accwhy=$accother="";
	$micgradeErr = $micwhyErr = $micotherErr = "";
	$accgradeErr = $accwhyErr = $accotherErr = "";
	$flag=0;
	//mic fill test
	//if ($_SERVER["REQUEST_METHOD"] == "POST")
	echo $_POST["micgrade"];
	if (!$_POST)
		{
		echo "in post";
		
		if (empty($_POST["micgrade"]))
			{$flag=1;
			$micgradeErr = "grade is required";
			}
		else
			{
			$micgrade = test_input($_POST["micgrade"]);
			if ($micgrade=='A')
				{}
			elseif ($micgrade!='A'&&empty($_POST["micwhy"]))
				{$flag=1;$micwhyErr = "Why is required";}
			else 
				{
				$micwhy=test_input($_POST["micwhy"]);
				if ($micwhy!='O')
					{}
				else if ($micwhy=='O'&& empty($_POST["micother"]))
					{$flag=1;$micotherErr="Other text box need fill";}
				else 
					{$micother=test_input($_POST["micother"]);}
				}			
			}		
		
	//acc fill test
		if (empty($_POST["accgrade"]))
			{$flag=1;$accgradeErr = "grade is required";}
		else
			{
			$accgrade = test_input($_POST["accgrade"]);
			if ($accgrade=='A')
				{}
			elseif ($accgrade!='A'&&empty($_POST["accwhy"]))
				{$flag=1;$accwhyErr = "why is required";}
			else 
				{
				$accwhy=test_input($_POST["accwhy"]);
				if ($accwhy!='O')
					{}
				else if ($accwhy=='O'&& empty($_POST["accother"]))
					{$flag=1;$accotherErr="Other text box need fill";}
				else 
					{$accother=test_input($_POST["accother"]);}
				}			
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