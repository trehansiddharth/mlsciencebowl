<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateall.php';

include 'updatequeue.php';
include 'updatedatabase.php';

$username = $_SESSION['username'];
$databasecount = $_SESSION['databasecount'];

$query = "SELECT * FROM queue";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
$queuecount = $rows;
$_SESSION['queuecount'] = $queuecount;

$rid = $_GET['rid'];

$result = mysql_query("SELECT * FROM tempquestions WHERE rid=$rid");
$rows = mysql_num_rows($result);

$full = FALSE;
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
	</head>
	<body>
		<script src="http://code.jquery.com/jquery.js"></script>
    	<script src="js/bootstrap.min.js"></script>
    	
		<div class="container-fluid">
			<div class="page-header">
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1></center>
				<ul class="breadcrumb">
					<li><a href="#">Home</a> <span class="divider">/</span></li>
					<li><a href="#"><?php echo $username ?></a> <span class="divider">/</span></li>
					<li><a href="#">Upload</a> <span class="divider">/</span></li>
					<li class="active">Preview</li>
				</ul>
				<ul class="nav nav-tabs">
					<?php
					$adminquery = mysql_query("SELECT * FROM userpermissions WHERE utype='Admin'");
					$adminpass = mysql_result($adminquery, 0, 'password');
					if ($_SESSION['loggedin'] == $adminpass)
					{
						echo '<li><a href="queue.php">Queue <span class="badge badge-warning">';
						echo $queuecount;
						echo '</span></a></li>';
						echo '<li><a href="database.php">Database <span class="badge badge-success">';
						echo $databasecount;
						echo '</span></a></li>';
						echo '<li><a href="users.php">Inventory</a></li>';
					}
					?>
					<li class="active"><a href="upload.php">Upload</a></li>
					<li><a href="profile.php">Profile</a></li>
				</ul>
				<h2>Upload</h2>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<p>Carefully verify that your round has been parsed correctly:</p>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span1">Number</th>
								<th class="span1">Question Type</th>
								<th class="span1">Answer Type</th>
								<th class="span1">Subject</th>
								<th class="span4">Question Text</th>
								<th class="span3">Choices</th>
								<th class="span1">Answer</th>
							</tr>
						</thead>
						<tbody>
							<?php							
							for ($j = 0 ; $j < $rows ; ++$j)
							{
								$qid1 = mysql_result($result, $j, 'qid1');
								$qid2 = mysql_result($result, $j, 'qid2');
								$qid1result = mysql_query("SELECT * FROM qbank WHERE qid=$qid1");
								
								echo '<tr>';
								
								$number = $j + 1;
								$questiontype = 'Toss-up';
								$subject = mysql_result($result, $j, 'subject');
								$text = mysql_result($qid1result, 0, 'text');
								$answer = mysql_result($qid1result, 0, 'answer');
								$choicew = mysql_result($qid1result, 0, 'choicew');
								$choicex = mysql_result($qid1result, 0, 'choicex');
								$choicey = mysql_result($qid1result, 0, 'choicey');
								$choicez = mysql_result($qid1result, 0, 'choicez');
								$choices = "<b>(W)</b> " . $choicew . "<br /><b>(X)</b> " . $choicex . "<br /><b>(Y)</b> " . $choicey . "<br /><b>(Z)</b> " . $choicez;
								
								if (is_null($choicew))
								{
									$answertype = 'Short Answer';
									$choices = '';
								}
								else
								{
									$answertype = 'Multiple Choice';
								}
								
								//echo '<td><label class="checkbox"><input type="checkbox" name="queuebox" id="' . $qid1 . '"/>' . $number . ' <a href="phpsql/editpage.php?qid=' . $qid1 . '">[...]</a>' . '</td>';
								
								echo "<td>$number <a href=\"phpsql/editpage.php?qid=$qid1&rid=$rid\">[edit]</a></td>";
								
								echo '<td>Toss-up</td>';
								
								echo "<td>$answertype</td>";
								
								echo "<td>$subject</a></td>";
								
								echo "<td>$text</td>";
								
								echo "<td>$choices</td>";
								
								echo "<td>$answer</td>";
								
								echo "</tr>\n";
								
								if (is_null($qid2))
								{
									
								}
								else
								{
									$full = TRUE;
									
									$qid2result = mysql_query("SELECT * FROM qbank WHERE qid=$qid2");
								
									echo '<tr>';
									
									$questiontype = 'Bonus';
									$subject = mysql_result($result, $j, 'subject');
									$text = mysql_result($qid2result, 0, 'text');
									$answer = mysql_result($qid2result, 0, 'answer');
									$choicew = mysql_result($qid2result, 0, 'choicew');
									$choicex = mysql_result($qid2result, 0, 'choicex');
									$choicey = mysql_result($qid2result, 0, 'choicey');
									$choicez = mysql_result($qid2result, 0, 'choicez');
									$choices = "<b>(W)</b> " . $choicew . "<br /><b>(X)</b> " . $choicex . "<br /><b>(Y)</b> " . $choicey . "<br /><b>(Z)</b> " . $choicez;
									
									if (is_null($choicew))
									{
										$answertype = 'Short Answer';
										$choices = '';
									}
									else
									{
										$answertype = 'Multiple Choice';
									}
								
									//echo '<td><label class="checkbox"><input type="checkbox" disabled="disabled" name="queuebox" id="x' . $qid1 . 'y' . $qid2 . '"/>' . $number . ' <a href="#">[...]</a>' . '</td>';
									
									echo "<td>$number <a href=\"phpsql/editpage.php?qid=$qid2&rid=$rid\">[edit]</a></td>";
									
									echo '<td>Bonus</td>';
									
									echo "<td>$answertype</td>";
									
									echo "<td>$subject</td>";
									
									echo "<td>$text</td>";
									
									echo "<td>$choices</td>";
									
									echo "<td>$answer</td>";
									
									echo "</tr>\n";
								}
							}
							?>
						</tbody>
					</table>
					<div style="float:right">
						<?php
						if ($full)
						{
							echo "<a href=\"phpsql/addpagebonus.php?rid=$rid\"><button class=\"btn\" type=\"button\">Add Another Question</button></a>";
						}
						else
						{
							echo "<a href=\"phpsql/addpage.php?rid=$rid\"><button class=\"btn\" type=\"button\">Add Another Question</button></a>";
						}
						?>
						<?php echo "<a href=\"accept.php?rid=$rid\"><button class=\"btn btn-primary\" type=\"button\">Accept</button></a>" ?>
					</div>
					<div style="float:left">
						<?php echo "<a href=\"phpsql/discard.php?rid=$rid\"><button class=\"btn\" type=\"button\">Discard Entire Round</button></a>" ?>
					</div>
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
