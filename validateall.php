<?php
$adminquery = mysql_query("SELECT * FROM userpermissions WHERE utype='Admin'");
$stdntquery = mysql_query("SELECT * FROM userpermissions WHERE utype='Stdnt'");

$adminpass = mysql_result($adminquery, 0, 'password');
$stdntpass = mysql_result($stdntquery, 0, 'password');

if ((($_SESSION['loggedin'] != $adminpass) && ($_SESSION['loggedin'] != $stdntpass)) || is_null($_SESSION['loggedin']))
{
	header("Location: index.html");
	exit;
}
?>
