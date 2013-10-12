<!DOCTYPE html>
<?php
session_start();

$db_hostname = "localhost";
$db_database = "mlsciencebowl";
$db_username = "mlsciencebowl";
$db_password = "planck";
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());

if ($_SESSION['loggedin'] != "debroglie")
{
	header("Location: index.html");
	exit;
}

// Load session variables

$username = $_SESSION['username'];
$queuecount = $_SESSION['queuecount'];
$databasecount = $_SESSION['databasecount'];
$readercount = $_SESSION['readercount'];

// Handle upload

$format = $_POST['format'];
$subjects = $_POST['subjects'];
$difficulty = $_POST['difficulty'];
$uid = $_SESSION['uid'];

if ($format != "")
{
	$file_path = "uploads/";
	$filename = basename($_FILES['file']['name'], '.doc');
	$file_path = $file_path . $filename . '.doc';
	
	$success = "danger";
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], $file_path))
	{
		chmod($file_path, 0755);
		$success = "success";
		
		$result = shell_exec('./toround.sh "' . $file_path . '" hs/web');
		
		echo $result;
		
		$hs_path = 'hs/web/' . $filename . '.round';
		chmod($hs_path, 0755);
		
		$filequery = "INSERT INTO queue VALUES(DEFAULT, $uid, 1, '$format', '$subjects', '$difficulty', 'Complete', '$hs_path')";
		$fileresult = mysql_query($filequery);
	}
}

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
					<li><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li><a href="assignments.php">Assignments</a></li>
					<li><a href="users.php">Users</a></li>
					<li><a href="reader.php">Reader <span class="badge badge-warning">
						<?php echo $readercount ?>
					</span></a></li>
					<li class="active"><a href="upload.php">Upload</a></li>
				</ul>
				<h2>Upload</h2>
			</div>
			<div class="row-fluid">
				<!--<div class="span2">
				</div>-->
				<div class="span12">
					<?php
					if ($format != "")
					{
						echo '<div class="alert alert-dismissable alert-' . $success . '">';
						echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
						if ($success == "success")
						{
							echo 'Your round has been uploaded successfully.';
						}
						else
						{
							echo 'An error occured while uploading your round.';
						}
						echo '</div>';
					}
					?>
					<form role="form" method="post" action="upload.php" enctype="multipart/form-data">
						<div class="form-group">
							<label>
								Assignment: 
								<select class="form-control" name="assignment">
									<option>Extra</option>
								</select>
							</label>
							<label>
								Format: 
								<select class="form-control" name="format">
									<option>Lightning</option>
									<option>Full</option>
									<option>Visual Bonus</option>
								</select>
							</label>
							<label>
								Difficulty: 
								<select class="form-control" name="difficulty">
									<option>Early Regionals</option>
									<option selected="selected">Late Regionals</option>
									<option>Middle Nationals</option>
									<option>Late Nationals</option>
								</select>
							</label>
							<label>
								Subject Spread: 
								<select class="form-control" name="subjects">
									<option selected="selected">Assorted</option>
									<option>Biology</option>
									<option>Chemistry</option>
									<option>Physics</option>
									<option>Math</option>
									<option>Earth and Space</option>
									<option>Energy</option>
								</select>
							</label> <br />
						</div>
						<div class="form-group">
							<input type="file" id="fileupload" name="file"> 
						</div><br />
						<button type="submit" class="btn btn-default btn-primary">Upload</button>
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