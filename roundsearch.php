<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

include 'updatequeue.php';
include 'updatedatabase.php';

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
		<link href="select2-3.4.3/select2.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="select2-3.4.3/select2.js"></script>
		<script type="text/javascript">
			$(document).ready(function() { 
				$("#authors").select2();
				$("#authornots").select2();
			});
		</script>
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
					<li class="active">Search for Rounds</li>
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
			<form class="form-inline" role="form" method="post" action="roundresults.php">
			<div class="row-fluid">
				<!--<div class="span2">
				</div>-->
				<div class="span12">
					<ul class="nav nav-pills">
						<li><a href="database.php">Search for Questions</a></li>
						<li class="active"><a href="roundsearch.php">Search for Rounds</a></li>
						<li><a href="retrieve.php">Retrieve Round using Round ID</a></li>
						<li><a href="#">Download Visual Bonus Rounds</a></li>
					</ul>
					<table class="table">
						<thead>
							<tr>
								<th class="span2">Criterion</th>
								<th class="span10">Options</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>Round type</b></td>
								<td>
									<label>
										Format: 
										<select class="form-control" name="format">
											<option>Lightning</option>
											<option>Full</option>
										</select>
									</label> <br />
									<label>
										Difficulty: 
										<select class="form-control" name="difficulty">
											<option>Early Regionals</option>
											<option selected="selected">Late Regionals</option>
											<option>Middle Nationals</option>
											<option>Late Nationals</option>
										</select>
									</label> <br />
									<label>
										Subject Spread: 
										<select class="form-control" name="spread">
											<option select="selected">Assorted</option>
											<option>Biology</option>
											<option>Chemistry</option>
											<option>Physics</option>
											<option>Math</option>
											<option>Earth and Space</option>
											<option>Energy</option>
										</select>
									</label>
								</td>
							</tr>
							<tr>
								<td><b>Authors</b></td>
								<td>
									<label>
										Written by:
										<select multiple id="authors" name="authors[]" style="width:500px">
											<?php
											for ($j = 0; $j < $urows; $j++)
											{
												$uid = mysql_result($userresults, $j, 'uid');
												$name = mysql_result($userresults, $j, 'name');
												
												echo "<option value=\"$uid\">$name</option>";
											}
											?>
										</select>
									</label>
									<label>
										Not written by:
										<select multiple id="authornots" name="authornots[]" style="width:500px">
											<?php
											for ($j = 0; $j < $urows; $j++)
											{
												$uid = mysql_result($userresults, $j, 'uid');
												$name = mysql_result($userresults, $j, 'name');
												
												echo "<option value=\"$uid\">$name</option>";
											}
											?>
										</select>
									</label>
								</td>
							</tr>
							<tr>
								<td><b>Question Details</b></td>
								<td>
									<label>
										Not read: 
										<select class="form-control" name="read">
											<option value="2 WEEK">In the past two weeks</option>
											<option selected="selected" value="3 MONTH">In the past three months</option>
											<option value="6 MONTH">In the past six months</option>
											<option value="1 YEAR">In the past year</option>
											<option value="ever">Ever</option>
											<option value="dontcare">Doesn't matter</option>									
										</select>
									</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<!--<div style="float:left"><button class="btn" type="button">Clear Criteria</button></div>-->
					<div style="float:right"><button class="btn btn-primary" type="submit">Start Reading</button></div>
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
