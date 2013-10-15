<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

$userresults = mysql_query("SELECT * FROM users");
$urows = mysql_num_rows($userresults);

$rid = $_GET['rid'];

mysql_query("UPDATE rounds SET timesread=timesread+1 WHERE rid=$rid");
mysql_query("UPDATE questions SET timesread=timesread+1 WHERE rid=$rid");

include 'updatedatabase.php';

include 'updatequeue.php';

$username = $_SESSION['username'];
$queuecount = $_SESSION['queuecount'];
$databasecount = $_SESSION['databasecount'];
?>
<html lang="en">
	<head>
		<title>mlsciencebowl: Science Bowl management system</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.css" rel="stylesheet" />
		<style>
			body {
				height: 100%;
			}
			.container {
				min-height: 100%;
			}
			.footer {
				height: 40px;
				margin-top: -40px;
			}
		</style>
		<link href="css/bootstrap-responsive.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery.js"></script>
	</head>
	<body>
    	<script src="js/bootstrap.min.js"></script>
		<div class="container-fluid">
			<div class="page-header">
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1></center>
				<ul class="breadcrumb">
					<li><a href="#">Home</a> <span class="divider">/</span></li>
					<li><a href="#">
						<?php echo $username ?>
					</a> <span class="divider">/</span></li>
					<li><a href="#">Database</a> <span class="divider">/</span></li>
					<li class="active">Round Reader</li>
				</ul>
				<ul class="nav nav-tabs">
					<li><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li class="active"><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li><a href="users.php">Inventory</a></li>
					<li><a href="upload.php">Upload</a></li>
					<li><a href="profile.php">Profile</a></li>
				</ul>
				<h2>Database</h2>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<p class="question" size="5">
						<?php
						$questions = mysql_query("SELECT * FROM questions WHERE rid=$rid");
						$numqs = mysql_num_rows($questions);
						$num = 0;
						for ($j = 0; $j < $numqs; ++$j)
						{
							$num++;
							$qid1 = mysql_result($questions, $j, 'qid1');
							$qid2 = mysql_result($questions, $j, 'qid2');
							$subject = mysql_result($questions, $j, 'subject');

							$qid1result = mysql_query("SELECT * FROM qbank WHERE qid=$qid1");
							if (!(is_null($qid2) || $qid2 == ""))
							{
								$qid2result = mysql_query("SELECT * FROM qbank WHERE qid=$qid2");
							}
							echo "<center><b>TOSS-UP</b></center><br /><br />";
							$text = mysql_result($qid1result, 0, 'text');
							$choicew = mysql_result($qid1result, 0, 'choicew');
							$choicex = mysql_result($qid1result, 0, 'choicex');
							$choicey = mysql_result($qid1result, 0, 'choicey');
							$choicez = mysql_result($qid1result, 0, 'choicez');
							$answer = mysql_result($qid1result, 0, 'answer');
							
							if (!is_null($choicew))
							{
								echo "<b>$num.</b> $subject; <i>Multiple Choice</i>: $text <br />";
								echo "<b>(W) </b>$choicew <br/>";
								echo "<b>(X) </b>$choicex <br/>";
								echo "<b>(Y) </b>$choicey <br/>";
								echo "<b>(Z) </b>$choicez <br/>";
							}
							else
							{
								echo "<b>$num.</b> $subject; <i>Short Answer</i>: $text <br />";
							}
						
							echo "<br /><b>ANSWER: </b> $answer";
						
							if (!(is_null($qid2) || $qid2 == ""))
							{
								echo "<center><b>BONUS</b></center><br /><br />";
								$text = mysql_result($qid2result, 0, 'text');
								$choicew = mysql_result($qid2result, 0, 'choicew');
								$choicex = mysql_result($qid2result, 0, 'choicex');
								$choicey = mysql_result($qid2result, 0, 'choicey');
								$choicez = mysql_result($qid2result, 0, 'choicez');
								$answer = mysql_result($qid2result, 0, 'answer');
								
								if (!is_null($choicew))
								{
									echo "<b>$num.</b> $subject; <i>Multiple Choice</i>: $text <br />";
									echo "<b>(W) </b>$choicew <br/>";
									echo "<b>(X) </b>$choicex <br/>";
									echo "<b>(Y) </b>$choicey <br/>";
									echo "<b>(Z) </b>$choicez <br/>";
								}
								else
								{
									echo "<b>$num.</b> $subject; <i>Short Answer</i>: $text <br />";
								}
							
								echo "<br /><b>ANSWER: </b> $answer";							
							}
							echo "<br /><hr /><br />";
						}
						?>
					</p>
				</div>
			</div>
		</div>
		<div id="footer">
      			<div class="container">
        			<center><p class="muted credit">Developed by <a href="https://github.com/trehansiddharth" target="_blank">Siddharth Trehan</a></p></center>
			</div>
		</div>
	</body>
</html>
