<!DOCTYPE html>
<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

include 'updatequeue.php';
include 'updatedatabase.php';

$result = mysql_query("SELECT * FROM questions WHERE timesread = 0");
if (!$result) die ("Database access failed: " . mysql_error());
$_SESSION['databasecount'] = mysql_num_rows($result);

$username = $_SESSION['username'];
$databasecount = $_SESSION['databasecount'];

$query = "SELECT * FROM queue WHERE pending=0";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
$queuecount = $rows;
$_SESSION['queuecount'] = $queuecount;
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
    	
    	<script type="text/javascript">
    	function checkAll(bx)
    	{
			var cbs = document.getElementsByName('queuebox');
			for(var i=0; i < cbs.length; i++)
			{
				cbs[i].checked = true;
			}
		}
		function submitselected(bx)
		{
			var cbs = document.getElementsByName('queuebox');
			for(var i=0; i < cbs.length; i++)
			{
				if (cbs[i].checked)
				{
					var xmlHttp = null;
					xmlHttp = new XMLHttpRequest();
					xmlHttp.open("POST", "dequeue.php?rid=" + String(cbs[i].id), false);
					xmlHttp.send(null);
					response = xmlHttp.responseText;
				}
			}
			location.reload();
		}
		function rejectselected(bx)
		{
			var cbs = document.getElementsByName('queuebox');
			for(var i=0; i < cbs.length; i++)
			{
				if (cbs[i].checked)
				{
					var xmlHttp = null;
					xmlHttp = new XMLHttpRequest();
					xmlHttp.open("POST", "rejectqueue.php?rid=" + String(cbs[i].id), false);
					xmlHttp.send(null);
					response = xmlHttp.responseText;
				}
			}
			location.reload();
		}
    	</script>
    	
		<div class="container-fluid">
			<div class="page-header">
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1></center>
				<ul class="breadcrumb">
					<li><a href="#">Home</a> <span class="divider">/</span></li>
					<li><a href="#"><?php echo $username ?></a> <span class="divider">/</span></li>
					<li class="active">Queue</li>
				</ul>
				<ul class="nav nav-tabs">
					<li class="active"><a href="queue.php">Queue <span class="badge badge-warning">
						<?php echo $queuecount ?>
					</span></a></li>
					<li><a href="database.php">Database <span class="badge badge-success">
						<?php echo $databasecount ?>
					</span></a></li>
					<li><a href="users.php">Inventory</a></li>
					<li><a href="upload.php">Upload</a></li>
					<li><a href="profile.php">Profile</a></li>
				</ul>
				<h2>Queue</h2>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<?php
						$pluralr = "s";
						$pluralis = "are";
						if ($rows == 1)
						{
							$pluralr = "";
							$pluralis = "is";
						}
						echo "<p><b> $rows round$pluralr</b> $pluralis awaiting submission:</p>";
					?>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span3">Name</th>
								<th class="span6">Assignment</th>
								<th class="span3">Type</th>
							</tr>
						</thead>
						<tbody>
							<?php							
							for ($j = 0 ; $j < $rows ; ++$j)
							{
								$iscomplete = mysql_result($result, $j, 'status');
								$trclass = '';
																
								if ($iscomplete == 'Complete')
								{
									$trclass = 'success';
								}
								elseif ($iscomplete == 'Partial')
								{
									$trclass = 'warning';
								}
								elseif ($iscomplete == 'Incomplete')
								{
									$trclass = 'error';
								}
								
								echo '<tr>'; //'<tr class="' . $trclass . '">';
								$rid = mysql_result($result, $j, 'rid');
								echo '<td><label class="checkbox"><input type="checkbox" name="queuebox" id="' . $rid . '"/><span class="glyphicon glyphicon-user"></span><b>';
								$uid = mysql_result($result,$j,'uploader');
								$userquery = "SELECT name FROM users WHERE uid = $uid";
								$userqueryresult = mysql_query($userquery);
								$name = mysql_result($userqueryresult, 0, 'name');
								echo "$name";
								echo '</b></label></td>';
								
								echo '<td><span class="glyphicon glyphicon-file"></span>';
								$assignment = mysql_result($result,$j,'assignment');
								echo $assignment . ' ';
								echo '</td>'; //<span class="badge badge-success">' . $iscomplete .'</span>
								
								echo '<td>';
								$format = mysql_result($result,$j,'format');
								$subjecttype = mysql_result($result, $j, 'subjecttype');
								echo $subjecttype . ' ' . $format;
								echo '</td>';
								
								echo "</tr>\n";
							}
							
							?>
							<!--<tr class="success">
								<td><label class="checkbox"><input type="checkbox" /><span class="glyphicon glyphicon-user"></span><b>Arvind Sundararajan</b></label></td>
								<td><span class="glyphicon glyphicon-file"></span>Thanksgiving Round #1 2013 <span class="badge badge-success">Complete</span></td>
								<td>Assorted Lightning</td>
							</tr>-->
						</tbody>
					</table>
					<div style="float:right">
						<button class="btn" type="button" onclick="rejectselected(this)">Reject Selected</button>
						<button class="btn btn-primary" type="button" onclick="submitselected(this)">Submit Selected</button>
					</div>
					<div style="float:left">
						<button class="btn" type="button" onclick="checkAll(this)"><span class="glyphicon glyphicon-trash"></span> Select All</button>
					</div>
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
