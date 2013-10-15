<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

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
	</head>
	<body>
		<script src="http://code.jquery.com/jquery.js"></script>
    	<script src="js/bootstrap.min.js"></script>
		<div class="container-fluid">
			<div class="page-header">
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1></center>
				<ul class="breadcrumb">
					<li><a href="#">Home</a> <span class="divider">/</span></li>
					<li><a href="#">
						<?php echo $username ?>
					</a> <span class="divider">/</span></li>
					<li><a href="#">Inventory</a> <span class="divider">/</span></li>
					<li class="active">Rounds</li>
				</ul>
				<ul class="nav nav-tabs">
					<li><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li class="active"><a href="users.php">Inventory</a></li>
					<li><a href="upload.php">Upload</a></li>
					<li><a href="profile.php">Profile</a></li>
				</ul>
				<h2>Inventory</h2>
			</div>
			<form class="form-inline" role="form" method="post" action="read.php">
			<div class="row-fluid">
				<div class="span12">
					<ul class="nav nav-pills">
						<li><a href="users.php">Users</a></li>
						<li class="active"><a href="seerounds.php">Rounds</a></li>
					</ul>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span1">Round ID</th>
								<th class="span2">Author</th>
								<th class="span2">Assignment</th>
								<th class="span1">Format</th>
								<th class="span2">Subject Spread</th>
								<th class="span2">Difficulty</th>
								<th class="span1">Number of Questions</th>
								<th class="span2">Last Read</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = mysql_query("SELECT * FROM rounds ORDER BY rid DESC");
							$rows = mysql_num_rows($results);
							
							for ($j = 0; $j < $rows; ++$j)
							{
								$rid = mysql_result($results, $j, 'rid');
								$uid = mysql_result($results, $j, 'uploader');
								$assignment = mysql_result($results, $j, 'assignment');
								$format = mysql_result($results, $j, 'format');
								$subjecttype = mysql_result($results, $j, 'subjecttype');
								$difficulty = mysql_result($results, $j, 'difficulty');
								$lastread = mysql_result($results, $j, 'lastread');
								$timesread = mysql_result($results, $j, 'timesread');
								
								if ($timesread == 0)
								{
									$lastread = "Never";
								}
								else
								{
									$lastread = new DateTime($lastread);
									$lastread = $lastread->format('M d, Y');
								}
								
								$numquestionsquery = mysql_query("SELECT * FROM questions WHERE rid=$rid");
								$numquestions = mysql_num_rows($numquestionsquery);
								
								$namequery = mysql_query("SELECT * FROM users WHERE uid=$uid");
								$name = mysql_result($namequery, 0, 'name');
								
								echo "<tr>";
								
								echo "<td>";
								echo $rid . " <a href=\"rounddisplay.php?rid=$rid\">[read]</a>";
								echo "</td>";
								
								echo "<td>";
								echo $name;
								echo "</td>";
								
								echo "<td>";
								echo $assignment;
								echo "</td>";
								
								echo "<td>";
								echo $format;
								echo "</td>";
								
								echo "<td>";
								echo $subjecttype;
								echo "</td>";
								
								echo "<td>";
								echo $difficulty;
								echo "</td>";
								
								echo "<td>";
								echo $numquestions;
								echo "</td>";
								
								echo "<td>";
								echo $lastread;
								echo "</td>";
								
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			</form>
		</div>
		<div id="footer">
      			<div class="container">
        			<center><p class="muted credit">Developed by <a href="https://github.com/trehansiddharth" target="_blank">Siddharth Trehan</a></p></center>
			</div>
		</div>
	</body>
</html>
