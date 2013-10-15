<!DOCTYPE html>
<?php
session_start();
$_SESSION['username'] = NULL;
$_SESSION['loggedin'] = NULL;
$_SESSION['uid'] = NULL;
$_SESSION['query'] = NULL;
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
				<center><h1>mlsciencebowl <br /><small>Science Bowl management system</small></h1><center>
			</div>
			<div class="row-fluid">
				<div class="span2"></div>
				<div class="span8">
					<div class="hero-unit">
						<center>
                    		<h1>Welcome to mlsciencebowl</h1>
                        	<p>mlsciencebowl makes it easy to upload, browse, and manage Mira Loma's science bowl question database. With it, you can search through questions, manage question deadlines, and read questions easily.</p>
                        	<p>
								<a data-toggle="modal" class="btn btn-primary btn-large" href="#login">Sign in</a>
							</p>
						</center>
                	</div>
				</div>
				<div class="span2"></div>
			</div>
		</div>
		<div id="footer">
      			<div class="container">
        			<center><p class="muted credit">Developed by <a href="https://github.com/trehansiddharth" target="_blank">Siddharth Trehan</a></p></center>
			</div>
		</div>
		<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Login</h4>
					</div>
					<form role="form" method="post" action="login.php">
						<div class="modal-body">
							<label>Name: <input class="form-control" type="text" name="username" id="username"></input></label>
							<label>Email: <input class="form-control" type="email" name="email" id="email"></input></label>
							<label>Password: <input class="form-control" type="password" name="password" id="password"></input></label>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
