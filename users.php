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
					<li class="active">Users</li>
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
						<li class="active"><a href="users.php">Users</a></li>
						<li><a href="seerounds.php">Rounds</a></li>
					</ul>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span3">Name</th>
								<th class="span4">Email</th>
								<th class="span4">Contributions</th>
								<th class="span1">Permissions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = mysql_query("SELECT * FROM users ORDER BY utype ASC");
							$rows = mysql_num_rows($results);
							
							for ($j = 0; $j < $rows; ++$j)
							{
								$uid = mysql_result($results, $j, 'uid');
								$username = mysql_result($results, $j, 'name');
								$email = mysql_result($results, $j, 'email');
								$permissions = mysql_result($results, $j, 'utype');
								
								$numroundsquery = mysql_query("SELECT * FROM rounds WHERE uploader=$uid");
								$numquestionsquery = mysql_query("SELECT * FROM questions WHERE uid=$uid");
								
								$numrounds = mysql_num_rows($numroundsquery);
								$numquestions = mysql_num_rows($numquestionsquery);
								
								$contributions = "$numrounds rounds and $numquestions questions";
								
								echo "<tr>";
								
								echo "<td>";
								echo $username;
								echo "</td>";
								
								echo "<td>";
								echo $email;
								echo "</td>";
								
								echo "<td>";
								echo $contributions;
								echo "</td>";
								
								echo "<td>";
								echo $permissions;
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
