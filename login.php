<!DOCTYPE html>
<?php
$db_hostname = "localhost";
$db_database = "mlsciencebowl";
$db_username = "mlsciencebowl";
$db_password = "planck";
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());

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
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['loggedin'] = "debroglie";
		$_SESSION['uid'] = $uid;
		
		$result = mysql_query("SELECT * FROM queue");
		if (!$result) die ("Database access failed: " . mysql_error());
		$_SESSION['queuecount'] = mysql_num_rows($result);
		
		$result = mysql_query("SELECT * FROM qunread");
		if (!$result) die ("Database access failed: " . mysql_error());
		$_SESSION['databasecount'] = mysql_num_rows($result);
		
		$result = mysql_query("SELECT * FROM lastadded");
		if (!$result) die ("Database access failed: " . mysql_error());
		$_SESSION['readercount'] = mysql_num_rows($result);
		
		header("Location: queue.php");
		exit;
	}
	else
	{
		echo "Your password was incorrect. Press the back button to try again.";
	}
}
else
{
		echo "Your username was incorrect. Press the back button to try again.";
}
?>

