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

if ($_SESSION['loggedin'] != "stefanboltzmann")
{
	header("Location: index.html");
	exit;
}

$rid = $_GET['rid'];

$result = mysql_query("SELECT * FROM queue WHERE rid=$rid");

if (!$result) die ("Database access failed: " . mysql_error());

$uploader = mysql_result($result, 0, 'uploader');
$assignment = mysql_result($result, 0, 'assignment');
$format = mysql_result($result, 0, 'format');
$subjecttype = mysql_result($result, 0, 'subjecttype');
$difficulty = mysql_result($result, 0, 'difficulty');
$status = mysql_result($result, 0, 'status');

mysql_query("INSERT INTO rounds VALUES($rid, $uploader, $assignment, '$format', '$subjecttype', '$difficulty', '$status')");
mysql_query("DELETE FROM queue WHERE rid=$rid");

$movequestions = mysql_query("SELECT * FROM tempquestions WHERE rid=$rid");

if (!$movequestions) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($movequestions);

for ($j = 0 ; $j < $rows ; ++$j)
{
	$qid1 = mysql_result($movequestions, $j, 'qid1');
	$qid2 = mysql_result($movequestions, $j, 'qid2');
	$subject = mysql_result($movequestions, $j, 'subject');
	
	mysql_query("INSERT INTO questions VALUES($qid1, $qid2, '$subject', $uploader, $rid, DEFAULT, 0, '$format', '$subjecttype', '$difficulty')");
	mysql_query("DELETE FROM tempquestions WHERE rid=$rid");
}

?>
