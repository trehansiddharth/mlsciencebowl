<?php
$adminquery = mysql_query("SELECT * FROM userpermissions WHERE utype='Admin'");

$adminpass = mysql_result($adminquery, 0, 'password');

if ($_SESSION['loggedin'] != $adminpass)
{
	header("Location: index.html");
	exit;
}
?>
