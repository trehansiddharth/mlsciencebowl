<!DOCTYPE html>
<?php
session_start();

include '../dbinfo.php';

include '../validateall.php';

$username = $_SESSION['username'];
$databasecount = $_SESSION['databasecount'];
$queuecount = $_SESSION['queuecount'];

$qid = $_GET['qid'];
$rid = $_GET['rid'];

$qbankresult = mysql_query("SELECT * FROM qbank WHERE qid=$qid");

?>
<html lang="en">
	<head>
		<title>mlsciencebowl: Science Bowl management system</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../css/bootstrap.css" rel="stylesheet" />
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
		<link href="../css/bootstrap-responsive.css" rel="stylesheet" />
	</head>
	<body>
		<script src="http://code.jquery.com/jquery.js"></script>
    	<script src="../js/bootstrap.min.js"></script>
    	
		<div class="container-fluid">
			<div class="page-header">
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1></center>
				<ul class="breadcrumb">
					<li><a href="#">Home</a> <span class="divider">/</span></li>
					<li><a href="#"><?php echo $username ?></a> <span class="divider">/</span></li>
					<li><a href="#">Upload</a> <span class="divider">/</span></li>
					<li><a href="#">Preview</a> <span class="divider">/</span></li>
					<li class="active">Edit Question</li>
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
					<form role="form" method="post" action="edit.php" enctype="multipart/form-data" accept-charset="UTF-8">
						<!--<label>
							Subject: 
							<input type="text" class="form-control" name="subject" value=
								<?php echo mysql_result($tempquestionsresult, 0, 'subject') ?>>
							</input>
						</label>-->
						<div class="form-group">
							<label>
								Question Text:<br />
								<?php
								echo '<textarea class="form-control" name="qtext" rows="5">';
								echo mysql_result($qbankresult, 0, 'text');
								echo '</textarea>';
								?>
							</label>
							<label>
								Choice W: 
								<?php
								echo '<input type="text" class="form-control" name="choicew" value="';
								echo mysql_result($qbankresult, 0, 'choicew');
								echo '"></input>';
								?>
							</label>
							<label>
								Choice X: 
								<?php
								echo '<input type="text" class="form-control" name="choicex" value="';
								echo mysql_result($qbankresult, 0, 'choicex');
								echo '"></input>';
								?>
							</label>
							<label>
								Choice Y: 
								<?php
								echo '<input type="text" class="form-control" name="choicey" value="';
								echo mysql_result($qbankresult, 0, 'choicey');
								echo '"></input>';
								?>
							</label>
							<label>
								Choice Z: 
								<?php
								echo '<input type="text" class="form-control" name="choicez" value="';
								echo mysql_result($qbankresult, 0, 'choicez');
								echo '"></input>';
								?>
							</label>
							<label>
								Answer: 
								<?php
								echo '<input type="text" class="form-control" name="answer" value="';
								echo mysql_result($qbankresult, 0, 'answer');
								echo '"></input>';
								?>
							</label>
							<input type="hidden" name="qid" value="<?php echo $qid ?>">
							<input type="hidden" name="rid" value="<?php echo $rid ?>">
						</div>
						<div style="float:right">
							<button class="btn btn-primary" type="submit">Update Question with Changes</button>
						</div>
						<div style="float:left">
							<?php echo "<a href=\"delete.php?qid=$qid&rid=$rid\"><button class=\"btn\" type=\"button\">Delete Question</button></a>" ?>
						</div>
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