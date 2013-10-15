<?php
$result = mysql_query("SELECT * FROM queue WHERE pending=0");
if (!$result) die ("Database access failed: " . mysql_error());
$_SESSION['queuecount'] = mysql_num_rows($result);
?>
