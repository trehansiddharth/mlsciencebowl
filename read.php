<!DOCTYPE html>
<?php
session_start();

//Log into mysql

$db_hostname = "localhost";
$db_database = "mlsciencebowl";
$db_username = "mlsciencebowl";
$db_password = "planck";
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());

//Load session variables

if ($_SESSION['loggedin'] != "stefanboltzmann")
{
	header("Location: index.html");
	exit;
}

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
					<li class="active">Reader</li>
				</ul>
				<ul class="nav nav-tabs">
					<li><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li><a href="assignments.php">Assignments</a></li>
					<li><a href="users.php">Users</a></li>
					<li class="active"><a href="reader.php">Reader <span class="badge badge-warning">
						<?php echo $readercount ?>
					</span></a></li>
					<li><a href="upload.php">Upload</a></li>
				</ul>
				<h2>Reader</h2>
			</div>
			<div class="row-fluid">
				<div class="span2"></div>
				<div class="span8">
					<center><h3>Physics</h3></center>
					<p><b>Multiple Choice</b></p>
					<div class="well">
					<p>The Reynolds number is a dimensionless factor that determines 

the onset of turbulence in a tube. Which of the following choices, when increased, would account 

for a decrease in the Reynolds number when all other factors remain constant?<br />

W) viscosity of the fluid<br />

X) density of the fluid<br />

Y) velocity of the fluid<br />

Z) diameter of the tube<br />

ANSWER: W) VISCOSITY OF THE FLUID</p>

</div>
<p><b>Short Answer</b></p>
<div class="well">

PHYSICS Short Answer Three resistors R1, R2, and R3 are connected in parallel to a battery 

with a potential difference of 18 V. If R1 = 3Ω, R2 = 6Ω, and R3 = 9Ω, what is the power 

delivered to R2, in watts?<br />

ANSWER: 54</div>
				</div>
				<div class="span2"></div>
			</div>
		</div>
		<div id="footer">
      			<div class="container">
        			<center><p class="muted credit">Developed by <a href="https://github.com/trehansiddharth" target="_blank">Siddharth Trehan</a></p></center>
			</div>
		</div>
	</body>
</html>
