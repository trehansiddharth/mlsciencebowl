<?php
session_start();

//Log into mysql

$db_hostname = "localhost";
$db_database = "mlsciencebowl";
$db_username = "mlsciencebowl";
$db_password = "planck";
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());

if ($_SESSION['loggedin'] != "debroglie")
{
	header("Location: index.html");
	exit;
}

$rid = $_GET['rid'];

$result = mysql_query("SELECT * FROM queue where rid=$rid");

if (!$result) die ("Database access failed: " . mysql_error());

$uploader = mysql_result($result, 0, 'uploader');
$assignment = mysql_result($result, 0, 'assignment');
$format = mysql_result($result, 0, 'format');
$subjecttype = mysql_result($result, 0, 'subjecttype');
$difficulty = mysql_result($result, 0, 'difficulty');
$status = mysql_result($result, 0, 'status');
$filename = mysql_result($result, 0, 'filename');

mysql_query("INSERT INTO rounds VALUES($rid, $uploader, $assignment, '$format', '$subjecttype', '$difficulty', '$status', '$filename')");
mysql_query("DELETE FROM queue WHERE rid=$rid");
?>
