<?php
session_start();

//Log into mysql

include 'dbinfo.php';

include 'validateadmin.php';

$query = $_SESSION['query'];

if (is_null($query))
{
	$num = 1;
	$format = $_POST['format'];
	$difficulty = $_POST['difficulty'];
	$biology = $_POST['biology'];
	$chemistry = $_POST['chemistry'];
	$physics = $_POST['physics'];
	$math = $_POST['math'];
	$ersp = $_POST['ersp'];
	$energy = $_POST['energy'];
	$authors = $_POST['authors'];
	$authornots = $_POST['authornots'];
	$read = $_POST['read'];
	
	$readquery = "";
	if ($read == "ever")
	{
		$readquery = "AND timesread=0";
	}
	else if ($read = "dontcare")
	{
		$readquery = "";
	}
	else
	{
		$readquery = "AND (lastread < NOW() - INTERVAL $read OR timesread=0)";
	}
	
	$subjarray = array();
	
	$i = 0;
	
	for ($j = $i; $j < $biology; ++$j)
	{
		$subjarray[$i + $j] = "Biology";
		++$i;
	}
	for ($j = 0; $j < $chemistry; ++$j)
	{
		$subjarray[$i + $j] = "Chemistry";
		++$i;
	}
	for ($j = 0; $j < $physics; ++$j)
	{
		$subjarray[$i + $j] = "Physics";
		++$i;
	}
	for ($j = 0; $j < $math; ++$j)
	{
		$subjarray[$i + $j] = "Math";
		++$i;
	}
	for ($j = 0; $j < $ersp; ++$j)
	{
		$subjarray[$i + $j] = "ERSP";
		++$i;
	}
	for ($j = 0; $j < $energy; ++$j)
	{
		$subjarray[$i + $j] = "Energy";
		++$i;
	}
	shuffle($subjarray);
	
	if (sizeof($authors) == 0)
	{
		$authorquery = "";
	}
	else
	{
		$authorquery = "AND uid IN (";
		$firsttime = TRUE;
		foreach ($authors as $author)
		{
			if ($firsttime)
			{
				$authorquery .= $author;
				$firsttime = FALSE;
			}
			else
			{
				$authorquery .= ", " . $author;
			}
		}
		$authorquery .= ")";
	}
	
	if (sizeof($authornots) == 0)
	{
		$authornotquery = "";
	}
	else
	{
		$authornotquery = "AND uid NOT IN (";
		$firsttime = TRUE;
		foreach ($authornots as $author)
		{
			if ($firsttime)
			{
				$authornotquery .= $author;
				$firsttime = FALSE;
			}
			else
			{
				$authornotquery .= ", " . $author;
			}
		}
		$authornotquery .= ")";
	}
	
	$assortedquery = "";
	
	$_SESSION['subjarray'] = $subjarray;
	$_SESSION['sarraysize'] = $i;
	$_SESSION['query'] = $query = "SELECT * FROM questions WHERE format='$format' AND difficulty='$difficulty' $authorquery $authornotquery $assortedquery $readquery";
}
else
{
	$num = $_GET['num'];
	$subjarray = $_SESSION['subjarray'];
	shuffle($subjarray);
	$i = $_SESSION['sarraysize'];
}

$nohits = TRUE;
for ($j = 0; $j < $i; ++$j)
{
	$rands = $subjarray[$j];
	
	$questions = mysql_query($query . " AND subject='$rands'");
	$rows = mysql_num_rows($questions);
	
	if ($rows != 0)
	{
		$nohits = FALSE;
		break;
	}
}

if ($nohits)
{
	header("Location: noresults.html");
	exit;
}
else
{
	$randq = mt_rand(0, $rows - 1);

	$qid1 = mysql_result($questions, $randq, 'qid1');
	$qid2 = mysql_result($questions, $randq, 'qid2');
	$qsubject = mysql_result($questions, $randq, 'subject');

	header("Location: display.php?num=$num&qid1=$qid1&qid2=$qid2&subject=$qsubject");
	exit;
}
?>
