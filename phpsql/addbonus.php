<?php
session_start();

include '../dbinfo.php';

include '../validateall.php';

$rid = $_POST['rid'];
$subject = $_POST['subject'];
$text1 = $_POST['qtext1'];
$choicew1 = $_POST['choicew1'];
$choicex1 = $_POST['choicex1'];
$choicey1 = $_POST['choicey1'];
$choicez1 = $_POST['choicez1'];
$answer1 = $_POST['answer1'];

$text2 = $_POST['qtext2'];
$choicew2 = $_POST['choicew2'];
$choicex2 = $_POST['choicex2'];
$choicey2 = $_POST['choicey2'];
$choicez2 = $_POST['choicez2'];
$answer2 = $_POST['answer2'];

$text1 = mysql_real_escape_string($text1);
$answer1 = mysql_real_escape_string($answer1);

if ($choicew1 != "")
{
	$choicew1 = mysql_real_escape_string($choicew1);
	$choicex1 = mysql_real_escape_string($choicex1);
	$choicey1 = mysql_real_escape_string($choicey1);
	$choicez1 = mysql_real_escape_string($choicez1);
	
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text1', '$choicew1', '$choicex1', '$choicey1', '$choicez1', '$answer1')");
}
else
{
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text1', NULL, NULL, NULL, NULL, '$answer1')");
}

$qid1 = mysql_insert_id();

$text2 = mysql_real_escape_string($text2);
$answer2 = mysql_real_escape_string($answer2);

if ($choicew1 != "")
{
	$choicew2 = mysql_real_escape_string($choicew2);
	$choicex2 = mysql_real_escape_string($choicex2);
	$choicey2 = mysql_real_escape_string($choicey2);
	$choicez2 = mysql_real_escape_string($choicez2);
	
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text2', '$choicew2', '$choicex2', '$choicey2', '$choicez2', '$answer2')");
}
else
{
	mysql_query("INSERT INTO qbank VALUES(DEFAULT, '$text2', NULL, NULL, NULL, NULL, '$answer2')");
}

$qid2 = mysql_insert_id();

mysql_query("INSERT INTO tempquestions VALUES($qid1, $qid2, '$subject', $rid)");

header("Location: ../preview.php?rid=$rid");
exit;

?>
