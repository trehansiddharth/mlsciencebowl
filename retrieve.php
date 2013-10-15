<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

include 'updatedatabase.php';

include 'updatequeue.php';

$username = $_SESSION['username'];
$queuecount = $_SESSION['queuecount'];
$databasecount = $_SESSION['databasecount'];

$userresults = mysql_query("SELECT * FROM users");
$urows = mysql_num_rows($userresults);
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
					<li class="active">Retrieve Round using Round ID</li>
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
					<ul class="nav nav-pills">
						<li><a href="database.php">Search for Questions</a></li>
						<li><a href="roundsearch.php">Search for Rounds</a></li>
						<li class="active"><a href="retrieve.php">Retrieve Round using Round ID</a></li>
						<li><a href="#">Download Visual Bonus Rounds</a></li>
					</ul>
					<form class="form-inline" role="form" method="get" action="rounddisplay.php">
						<input type="number" name="rid"></input>
						<button class="btn btn-primary" type="submit">Retrieve</button>
					</form>
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
