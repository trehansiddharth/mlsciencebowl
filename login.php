<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$result = mysql_query("SELECT * FROM users WHERE name = '$username'");
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);

if ($rows == 1)
{
	$utype = mysql_result($result, 0, 'utype');
	$uid = mysql_result($result, 0, 'uid');
	$result2 = mysql_query("SELECT * FROM userpermissions WHERE utype = '$utype'");
	$expectedpass = mysql_result($result2, 0, 'password');
	
	if ($expectedpass == $password)
	{
		$_SESSION['username'] = $username;
		$_SESSION['loggedin'] = $password;
		$_SESSION['uid'] = $uid;
		$_SESSION['query'] = NULL;
		
		include 'updatequeue.php';
		
		include 'updatedatabase.php';
		
		if ($utype == "Admin")
		{
			header("Location: queue.php");
		}
		else if ($utype == "Stdnt")
		{
			header("Location: upload.php");
		}
		exit;
	}
	else
	{
		echo "Your password was incorrect. Press the back button to try again.";
	}
}
else
{
		$passwordquery = mysql_query("SELECT * FROM userpermissions WHERE password = '$password'");
		$hits = mysql_num_rows($passwordquery);
		
		if ($hits == 1)
		{
			$utype = mysql_result($passwordquery, 0, 'utype');
			$adduserquery = mysql_query("INSERT INTO users VALUES(DEFAULT, '$username', '$email', '$utype')");
			$uid = mysql_insert_id();
			
			$_SESSION['username'] = $username;
			$_SESSION['loggedin'] = $password;
			$_SESSION['uid'] = $uid;
			$_SESSION['query'] = NULL;
			
			$result = mysql_query("SELECT * FROM queue");
			if (!$result) die ("Database access failed: " . mysql_error());
			$_SESSION['queuecount'] = mysql_num_rows($result);
			
			$result = mysql_query("SELECT * FROM questions WHERE timesread = 0");
			if (!$result) die ("Database access failed: " . mysql_error());
			$_SESSION['databasecount'] = mysql_num_rows($result);
			
			if ($utype == "Admin")
			{
				header("Location: queue.php");
			}
			else if ($utype == "Stdnt")
			{
				header("Location: upload.php");
			}
			exit;
		}
		else
		{
			echo "You are not registered on the system. Please press the back button to try again.";
		}
}
?>


