<?php
header('Content-Type: text/html; charset=utf8');
$con = mysql_connect("_______","user","_______");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER_SET_CLIENT='utf8'");
mysql_query("SET CHARACTER_SET_RESULTS='utf8'");
mysql_select_db("dbname", $con);
?>