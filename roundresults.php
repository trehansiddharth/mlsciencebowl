<!DOCTYPE html>
<?php
session_start();

//Log into mysql

include 'dbinfo.php';

include 'validateadmin.php';

$format = $_POST['format'];
$difficulty = $_POST['difficulty'];
$spread = $_POST['spread'];
$authors = $_POST['authors'];
$authornots = $_POST['authornots'];
$read = $_POST['read'];

if (sizeof($authors) == 0)
{
	$authorquery = "";
}
else
{
	$authorquery = "AND uploader IN (";
	$firsttime = TRUE;
	foreach ($authors as $author)
	{
		if ($firsttime)
		{
			$authorquery .= $author;
			$firsttime = FALSE;
		}
		else
		{
			$authorquery .= ", " . $author;
		}
	}
	$authorquery .= ")";
}


	
$readquery = "";
if ($read == "ever")
{
	$readquery = "AND timesread=0";
}
else if ($read = "dontcare")
{
	$readquery = "";
}
else
{
	$readquery = "AND (lastread < NOW() - INTERVAL $read OR timesread=0)";
}

if (sizeof($authornots) == 0)
{
	$authornotquery = "";
}
else
{
	$authornotquery = "AND uploader NOT IN (";
	$firsttime = TRUE;
	foreach ($authornots as $author)
	{
		if ($firsttime)
		{
			$authornotquery .= $author;
			$firsttime = FALSE;
		}
		else
		{
			$authornotquery .= ", " . $author;
		}
	}
	$authornotquery .= ")";
}

$assortedquery = "";

$rquery = mysql_query("SELECT * FROM rounds WHERE format='$format' AND difficulty='$difficulty' AND subjecttype='$spread' $authorquery $authornotquery $assortedquery $readquery");
$num_hits = mysql_num_rows($rquery);

if ($num_hits == 0)
{
	header("Location: noresults.html");
	exit;
}

//Load session variables

$username = $_SESSION['username'];
$queuecount = $_SESSION['queuecount'];
$databasecount = $_SESSION['databasecount'];

//$userresults = mysql_query("SELECT * FROM users");
//$urows = mysql_num_rows($userresults);
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
					<li><a href="#">Search for Rounds</a> <span class="divider">/</span></li>
					<li class="active">Round Search Results</li>
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
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="span1">Round ID</th>
								<th class="span2">Author</th>
								<th class="span2">Assignment</th>
								<th class="span1">Format</th>
								<th class="span2">Subject Spread</th>
								<th class="span2">Difficulty</th>
								<th class="span1">Number of Questions</th>
								<th class="span2">Last Read</th>
							</tr>
						</thead>
						<tbody>
							<?php							
							for ($j = 0; $j < $num_hits; ++$j)
							{
								$rid = mysql_result($rquery, $j, 'rid');
								$uid = mysql_result($rquery, $j, 'uploader');
								$assignment = mysql_result($rquery, $j, 'assignment');
								$format = mysql_result($rquery, $j, 'format');
								$subjecttype = mysql_result($rquery, $j, 'subjecttype');
								$difficulty = mysql_result($rquery, $j, 'difficulty');
								$lastread = mysql_result($rquery, $j, 'lastread');
								$timesread = mysql_result($rquery, $j, 'timesread');
								
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
								
								$namequery = mysql_query("SELECT * FROM users WHERE uid=$uid");
								$name = mysql_result($namequery, 0, 'name');
								
								echo "<tr>";
								
								echo "<td>";
								echo $rid . " <a href=\"rounddisplay.php?rid=$rid\">[read]</a>";
								echo "</td>";
								
								echo "<td>";
								echo $name;
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
