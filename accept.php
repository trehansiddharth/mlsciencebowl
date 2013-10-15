<?php
session_start();

include 'dbinfo.php';

include 'validateall.php';

$rid = $_GET['rid'];

mysql_query("UPDATE queue SET pending=0 WHERE rid=$rid");

header("Location: upload.php?success=success");
exit;
?>
