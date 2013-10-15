<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

$rid = $_GET['rid'];

$result = mysql_query("SELECT * FROM queue WHERE rid=$rid");

if (!$result) die ("Database access failed: " . mysql_error());

$uploader = mysql_result($result, 0, 'uploader');
$assignment = mysql_result($result, 0, 'assignment');
$format = mysql_result($result, 0, 'format');
$subjecttype = mysql_result($result, 0, 'subjecttype');
$difficulty = mysql_result($result, 0, 'difficulty');

mysql_query("INSERT INTO rounds VALUES($rid, $uploader, '$assignment', '$format', '$subjecttype', '$difficulty', 0, DEFAULT)");
mysql_query("DELETE FROM queue WHERE rid=$rid");

$movequestions = mysql_query("SELECT * FROM tempquestions WHERE rid=$rid");

if (!$movequestions) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($movequestions);

for ($j = 0 ; $j < $rows ; ++$j)
{
	$qid1 = mysql_result($movequestions, $j, 'qid1');
	$qid2 = mysql_result($movequestions, $j, 'qid2');
	
	if (is_null($qid2))
	{
		$qid2 = "NULL";
	}
	
	$subject = mysql_result($movequestions, $j, 'subject');
	
	mysql_query("INSERT INTO questions VALUES($qid1, $qid2, '$subject', $uploader, $rid, DEFAULT, 0, '$format', '$subjecttype', '$difficulty')");
	mysql_query("DELETE FROM tempquestions WHERE rid=$rid");
}

?>
