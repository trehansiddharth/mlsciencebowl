<?php
$result = mysql_query("SELECT * FROM questions WHERE timesread = 0");
if (!$result) die ("Database access failed: " . mysql_error());
$_SESSION['databasecount'] = mysql_num_rows($result);
?>
