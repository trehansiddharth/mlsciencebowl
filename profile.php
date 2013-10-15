<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateall.php';

// Load session variables

$username = $_SESSION['username'];
$queuecount = $_SESSION['queuecount'];
$databasecount = $_SESSION['databasecount'];

$uid = $_SESSION['uid'];
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
					<li class="active">Upload</li>
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
					<li><a href="upload.php">Upload</a></li>
					<li class="active"><a href="profile.php">Profile</a></li>
				</ul>
				<h2>Profile</h2>
			</div>
			<div class="row-fluid">
				<!--<div class="span2">
				</div>-->
				<div class="span12">
					Here are the rounds that you have uploaded:
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span1">Round ID</th>
								<th class="span2">Assignment</th>
								<th class="span2">Format</th>
								<th class="span2">Subject Spread</th>
								<th class="span2">Difficulty</th>
								<th class="span1">Number of Questions</th>
								<th class="span2">Last Read</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = mysql_query("SELECT * FROM rounds WHERE uploader=$uid ORDER BY rid DESC");
							$rows = mysql_num_rows($results);
							
							for ($j = 0; $j < $rows; ++$j)
							{
								$rid = mysql_result($results, $j, 'rid');
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
								
								echo "<tr>";
								
								echo "<td>";
								echo $rid;
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
		</div>
		<div id="footer">
      			<div class="container">
        			<center><p class="muted credit">Developed by <a href="https://github.com/trehansiddharth" target="_blank">Siddharth Trehan</a></p></center>
			</div>
		</div>
	</body>
</html>
