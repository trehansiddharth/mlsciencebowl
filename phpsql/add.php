<?php
session_start();

include '../dbinfo.php';

include '../validateall.php';

$rid = $_POST['rid'];
$subject = $_POST['subject'];
$text = $_POST['qtext'];
$choicew = $_POST['choicew'];
$choicex = $_POST['choicex'];
$choicey = $_POST['choicey'];
$choicez = $_POST['choicez'];
$answer = $_POST['answer'];

$text = mysql_real_escape_string($text);
$answer = mysql_real_escape_string($answer);

if ($choicew != "")
{
	$choicew = mysql_real_escape_string($choicew);
	$choicex = mysql_real_escape_string($choicex);
	$choicey = mysql_real_escape_string($choicey);
	$choicez = mysql_real_escape_string($choicez);
	
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text', '$choicew', '$choicex', '$choicey', '$choicez', '$answer')");
}
else
{
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text', NULL, NULL, NULL, NULL, '$answer')");
}

$qid = mysql_insert_id();

mysql_query("INSERT INTO tempquestions VALUES($qid, NULL, '$subject', $rid)");

header("Location: ../preview.php?rid=$rid");
exit;

?>
