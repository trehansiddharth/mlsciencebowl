<!DOCTYPE html>
<?php
session_start();

include '../dbinfo.php';

include '../validateall.php';

$username = $_SESSION['username'];
$databasecount = $_SESSION['databasecount'];
$queuecount = $_SESSION['queuecount'];

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
					<form role="form" method="post" action="addbonus.php" enctype="multipart/form-data" accept-charset="UTF-8">
						<div class="form-group">
							<h3>Toss-up</h3>
							<label>
								Subject: 
								<input type="text" class="form-control" name="subject"></input>
							</label>
							<label>
								Question Text:<br />
								<textarea class="form-control" name="qtext1" rows="5"></textarea>
							</label>
							<label>
								Choice W: 
								<input type="text" class="form-control" name="choicew1"></input>
							</label>
							<label>
								Choice X: 
								<input type="text" class="form-control" name="choicex1"></input>
							</label>
							<label>
								Choice Y: 
								<input type="text" class="form-control" name="choicey1"></input>
							</label>
							<label>
								Choice Z: 
								<input type="text" class="form-control" name="choicez1"></input>
							</label>
							<label>
								Answer: 
								<input type="text" class="form-control" name="answer1"></input>
							</label>
							<h3>Bonus</h3>
							<label>
								Question Text:<br />
								<textarea class="form-control" name="qtext2" rows="5"></textarea>
							</label>
							<label>
								Choice W: 
								<input type="text" class="form-control" name="choicew2"></input>
							</label>
							<label>
								Choice X: 
								<input type="text" class="form-control" name="choicex2"></input>
							</label>
							<label>
								Choice Y: 
								<input type="text" class="form-control" name="choicey2"></input>
							</label>
							<label>
								Choice Z: 
								<input type="text" class="form-control" name="choicez2"></input>
							</label>
							<label>
								Answer: 
								<input type="text" class="form-control" name="answer2"></input>
							</label>
							<input type="hidden" name="rid" value="<?php echo $rid ?>">
						</div>
						<div style="float:right">
							<button class="btn btn-primary" type="submit">Add Question</button>
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
