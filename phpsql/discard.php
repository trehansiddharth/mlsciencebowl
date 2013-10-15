<?php
session_start();

include '../dbinfo.php';

include '../validateall.php';

$rid = $_GET['rid'];

$todelete = mysql_query("SELECT * FROM tempquestions WHERE rid=$rid");
$deleterows = mysql_num_rows($todelete);

for ($j = 0; $j < $deleterows; $j++)
{
	$qid1 = mysql_result($todelete, $j, 'qid1');
	$qid2 = mysql_result($todelete, $j, 'qid2');
	
	mysql_query("DELETE FROM qbank WHERE qid=$qid1");
	mysql_query("DELETE FROM qbank WHERE qid=$qid2");
}

mysql_query("DELETE FROM queue WHERE rid=$rid");
mysql_query("DELETE FROM tempquestions WHERE rid=$rid");

$query = "SELECT * FROM queue";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
$queuecount = $rows;
$_SESSION['queuecount'] = $queuecount;

header("Location: ../upload.php?success=error");
exit;

?>
