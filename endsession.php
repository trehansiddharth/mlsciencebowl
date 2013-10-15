<?php
session_start();

include 'dbinfo.php';

include 'validateadmin.php';

$_SESSION['query'] = NULL;
$_SESSION['subjarray'] = NULL;
$_SESSION['sarraysize'] = NULL;

include 'updatequeue.php';
include 'updatedatabase.php';

header("Location: database.php");
exit;

?>
