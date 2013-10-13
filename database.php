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
					<li><a href="#">Database</a> <span class="divider">/</span></li>
					<li class="active">Search for Questions</li>
				</ul>
				<ul class="nav nav-tabs">
					<li><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li class="active"><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li><a href="users.php">Users</a></li>
					<!--<li><a href="reader.php">Reader <span class="badge badge-warning">
						<?php echo $readercount ?>
					</span></a></li>-->
					<li><a href="upload.php">Upload</a></li>
				</ul>
				<h2>Database</h2>
			</div>
			<form class="form-inline" role="form" method="post" action="read.php">
			<div class="row-fluid">
				<!--<div class="span2">
				</div>-->
				<div class="span12">
					<ul class="nav nav-pills">
						<li class="active"><a href="#">Search for Questions</a></li>
						<li><a href="#">Search for Rounds</a></li>
						<li><a href="#">Download Visual Bonus Rounds</a></li>
						<li><a href="#">Download Raw Rounds</a></li>
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
											<option>Visual Bonus</option>
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
									</label>
								</td>
							</tr>
							<tr>
								<td><b>Subject Priorities</b></td>
								<td>
									<label>
										Biology: 
										<select class="form-control" name="biology">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label> <br />
									<label>
										Chemistry: 
										<select class="form-control" name="chemistry">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label> <br />
									<label>
										Physics: 
										<select class="form-control" name="physics">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label> <br />
									<label>
										Math: 
										<select class="form-control" name="math">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label> <br />
									<label>
										Earth and Space: 
										<select class="form-control" name="ersp">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label> <br />
									<label>
										Energy/General Science: 
										<select class="form-control" name="energy">
											<option>High</option>
											<option selected="selected">Medium</option>
											<option>Low</option>
											<option>None</option>
										</select>
									</label>
								</td>
							</tr>
							<tr>
								<td><b>Authors</b></td>
								<td>
									<div class="input-group">
										<!--<button class="btn btn-default" type="button">Select</button><br />-->
										<table class="table table-bordered">
											<thead>
												<td class="span2"><b>Names</b></td>
											</thead>
												<tr>
													<td>
														<input type="text" class="form-control" id="author" placeholder="Enter name to add"></input>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="input-group">
										<!--<button class="btn btn-default" type="button">Select</button><br />-->
										<table class="table table-bordered">
											<thead>
												<td class="span2"><b>Groups</b></td>
											</thead>
											<tbody>
												<tr>
													<td>
														<input type="text" class="form-control" id="author" placeholder="Enter group to add"></input>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td><b>Question Sources</b></td>
								<td>
									<label><input type="checkbox" checked="checked" name="assorted" /> Assorted Rounds</label> <br />
									<label><input type="checkbox" checked="checked" name="subject" /> Subject Rounds</label> <br />
								</td>
							</tr>
							<tr>
								<td><b>Question Details</b></td>
								<td>
									<label>
										Not read: 
										<select class="form-control" name="read">
											<option>In the past two weeks</option>
											<option selected="selected">In the past three months</option>
											<option>In the past six months</option>
											<option>In the past year</option>
											<option>Ever</option>
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
