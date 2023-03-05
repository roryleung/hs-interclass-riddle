<?php
session_start();
include 'func.php';
/* include 'db_.php';
if (isset($_COOKIE['uid']) && isset($_COOKIE['pwd'])) {
$uid = $_COOKIE['uid'];
$pwd = $_COOKIE['pwd'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='$uid' AND pwd='$pwd'");
if (mysql_num_rows($query)!=1) {

} else {
exit('<meta http-equiv="refresh" content="0;url=main.php" />');
}
} else {

} */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Computer Club - Inter-class Puzzle-solving Contest</title>
	<link href="jquery-ui-1.10.1.custom.css" rel="stylesheet">
	<script src="jquery-1.9.1.js"></script>
	<script src="jquery-ui-1.10.1.custom.js"></script>
	<script src="js.js"></script>
    <script src="jquery.cookie.js"></script>
	<script>

	$(function() {
		$( "#accordion" ).accordion();


		
		$( "input:button" ).button();
		$( "#radioset" ).buttonset();
		
		$("#tabs").tabs({
		
        select: function(event, ui) {
            $(ui.panel).animate({opacity:0.1});
        },
        show: function(event, ui) {
			$(ui.panel).slideToggle();
            $(ui.panel).animate({opacity:1.0},1000);
        }
	});



		$( "#alert" ).dialog({
			width:450,
			autoOpen: false,
			modal:true,
			

		})

	function popup(cont) {
	$("#alert").html("<p>"+cont+"</p>");
	$("#alert").dialog( "open" );

	}
	$( "input[type='text'],input[type='password']" ).addClass("ui-widget ui-widget-content ui-corner-all");
	
	$("#loginsb").click(
	function() {
	$.post("json.php",
	{e:'log',u:$("#uid").val(),p:$("#pwd").val()},
	function(resp) {
	if (resp.info=="") {
	popup("登入成功！");
	setTimeout(function(){window.location.replace("main.php")},800);
	
	}　else{
	popup(resp.info);
	}
	},
	"json"
	);
	}
			);
});
	</script>
	<style>
#tabs .ui-tabs-nav { 
    height: 2.35em; 
	font-size:22px;
    text-align: center; 
} 
#tabs .ui-tabs-nav li { 
    display: inline-block; 
    float: none; 
    top: 0px; 
    margin: 0em; }
body{
		font-size:14px;
		margin: 5px;
		color:white;
	}
a:link,a:visited {
	color:#999999;
	text-decoration:underline;
}
a:hover,a:active {

	color:#DDDDDD;
	text-decoration:underline;
}
	</style>
</head>
<body bgcolor="#000">
<p style="font-size:26px;font-family:'Consolas';" align="center">Computer Club - Online Riddle Competition II</p>
<div id="tabs" style="margin-top:30px;" >
<ul>
<li><a href="#homepage" id="pg-1">Start Page</a></li>
<li><a href="#log" id="pg-2">Login</a></li>
<li><a href="#rules" id="pg-3">Rules</a></li>
<li><a href="#help" id="pg-4">Need Help?</a></li>
</ul>
<div id="homepage">
<p>Welcome to the competition！<br />
To obtain the password for each stage, you will have to look for clues yourselves!<br />
</p><br />
<div align="center"><img src="images/logo.png" width="360" height="375" /></div>

</div>
<div id="rules">
<p>The rules of the contest are as follows:</p><br />
<ul><li>There are a total of 20 stages。</li></ul>
<ul><li>All passwords for each stage should have only lowercase alphabets and numbers.</li></ul>
<ul><li>You may need to use search engines.</li></ul>
<ul><li>The competition will last for 1 hour and 30 minutes.</li></ul>
<ul><li>The class that solves the most stages wins the competition.</li></ul>
</div>
<div id="log">
<div align="center" style="margin-top:20px;">
<table cellpadding="10" cellspacing="10">
<tr>
<td>Username:</td><td><input type="text"  id="uid"/></td>
</tr>
<tr>
<td>Password:</td><td><input type="password" id="pwd" /></td>
</tr>
</table>

<p align="center"><input type="button" id="loginsb" value="Submit" onClick="Login()"/></p>
<p align="center" style="font-size:0.8em">
</div>
</div>
<div id="help">
<p>Should you have any questions, please approach any of the club committee members :)!</p>
</div>
</div>
<div id="alert" title="System Message">

</div>
</body>
</html>
