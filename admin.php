<?php
session_start();
include 'func.php';
include 'db_.php';
$goalstg = mysql_fetch_array(mysql_query("SELECT * FROM orgen WHERE info='goal_stg'"));
$goalstg = $goalstg['ndata'];
if (isset($_COOKIE['uid']) && isset($_COOKIE['pwd'])) {
$uid = $_COOKIE['uid'];
$pwd = $_COOKIE['pwd'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='admin' AND pwd='$pwd'");
if (mysql_num_rows($query)!=1) {
header("location:main.php");
exit();
} else {

}
} else {
header("location:index.php");
exit();
}
if ($_POST['e']=="stg") {
$id = $_POST['id'];
$getstg = mysql_query("SELECT * FROM orstg WHERE id='".$id."'");

	if (mysql_num_rows($getstg)!=1) {
	echo '{ "info":"N" }';
	} else {
	$stage = mysql_fetch_array($getstg);
	$output = $stage['content'];
	
	$cont = preg_replace('/^\s+|\n|\r|\s+$/m', '', $output);
	
	echo '{
	"info":"",
	"tit":"'.$stage['title'].'",
	"cont":"'.$cont.'",
	"scr":"'.$stage['score'].'",
	"ans":"'.$stage['ans'].'" }';
	}
} elseif($_POST['e']=="svstg") {
$id = $_POST['id'];
$getstg = mysql_query("SELECT * FROM orstg WHERE id='".$id."'");
	if (mysql_num_rows($getstg)!=1) {
	echo '{ "info":"N" }';
	} else {
$tit = $_POST['tit'];
$ans = $_POST['ans'];
$content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $_POST['content']);
$cont = str_replace('"', "", $content);
$cont = str_replace('"', "", $cont);
mysql_query("UPDATE orstg SET title='".$tit."', ans='".$ans."',score='".$_POST['scr']."' WHERE id='".$id."'");
mysql_query("UPDATE orstg SET content='".$content."' WHERE id='".$id."'");
echo '{ "info":"Stage '.$id.' updated successfully!" }';
	}
}	
elseif($_POST['e']=="svgen") {
$optl = $_POST['optl'];
$stat = $_POST['stat'];
mysql_query("UPDATE orgen SET data='".$stat."' WHERE id='1'");
mysql_query("UPDATE orgen SET ndata='".$optl."' WHERE id='2'");

echo '{ "info":"General settings updated successfully!" }';
}
elseif($_POST['e']=="nus") {
$u = $_POST['u'];
$p = salt($_POST['p']);
mysql_query("INSERT INTO oruser (uid, pwd)
VALUES ('".$u."', '".$p."')");
echo '{ "info":"New user inserted!" }';
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Computer Club - Inter-class Puzzle-solving Contest - Admin Page</title>
	<link href="jquery-ui-1.10.1.custom.css" rel="stylesheet">
	<script src="jquery-1.9.1.js"></script>
	<script src="jquery-ui-1.10.1.custom.js"></script>
	<script src="js.js"></script>
    <script src="jquery.cookie.js"></script>
	<script>

	$(function() {
		$( "#accordion" ).accordion();
		$("#stgid").spinner({
			min:0,
			max:28,
			stop:function(e,ui){
				$.post("admin.php",
				{e:'stg',id:$("#stgid").val()},
				function(resp) {
				if (resp.info=="") {
				$("#stgtit").val(resp.tit);
				$("#stgans").val(resp.ans);
				$("#stgcont").val(resp.cont);
				$("#stgscr").val(resp.scr);
				} else {
				$("#stgtit").val("");
				$("#stgans").val("");
				$("#stgcont").val("");
				$("#stgscr").val("");
				}
				},"json");
			}
		});
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
	$( "input[type='text'],input[type='password'],textarea" ).addClass("ui-widget ui-widget-content ui-corner-all");

	$("#pg-3").click(function() {
	$.post("json.php",
	{e:'rnk',u:$.cookie("uid")},
	function(resp) {
	var out = "<table border='1' align='center' cellpadding='10' bordercolor='#CCC' width='700'><tr><td>名次</td><td>班代表</td><td>破關數</td><td>最後破關時間</td></tr>";
	$("#rank").html(out+resp.out);
	},
	"json"
	);
	});
	$("#svgen").click(function() {
				$.post("admin.php",
				{e:'svgen',optl:$("#optl").val(),stat:$("input[name='status']:checked").attr('id')},
				function(resp) {
				
				popup(resp.info);
				},"json");
	})
	$("#svstg").click(function() {
				$.post("admin.php",
				{e:'svstg',id:$("#stgid").val(),tit:$("#stgtit").val(),ans:$("#stgans").val(),content:$("#stgcont").val(),scr:$("#stgscr").val()},
				function(resp) {
				if (resp.info=="N" || resp.info=="") {
				popup("Stage Modification error!")
				} else {
				popup(resp.info);
				}
				},"json");
	})
	$("#newus").click(function() {
				$.post("admin.php",
				{e:'nus',u:$("#newu").val(),p:$("#newp").val()},
				function(resp) {
				popup(resp.info);
				},"json");
				$("#newu").val("");
				$("#newp").val("");
	});
	$("#demostg").click(function() {
	popup($("#stgcont").val());
	});
	$("#time").click(function() {
	$("#optl").val(Math.round(new Date().getTime()/1000));
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
<p style="font-size:25px;font-family:'Consolas';" align="center">Computer Club - Inter-class Online Riddle Competition - Admin Page</p>
<div id="tabs" style="margin-top:30px;" >
<ul>
<li><a href="#gen" id="pg-1">Competition Setting</a></li>
<li><a href="#stg" id="pg-2">Stage Management</a></li>
<li><a href="#nus" id="pg-4">Add Users</a></li>
<li><a href="#rank" id="pg-3">Current Ranking</a></li>
</ul>
<div id="gen">
<table  align='center' cellpadding='10' width='700'>
<tr>
<td>比賽狀態</td><td><div id="radioset">
		<input type="radio" id="open" name="status"><label for="open">Open</label>
		<input type="radio" id="close" name="status" ><label for="close">Close</label>
	</div></td>
    </tr>
<tr>
<td>Open until：(<a href="#" id="time">Current Timestamp</a>)</td>
<td><input type="text" id="optl" value="2147483647" /></td>
</tr>

</table>
<p align="center"><input type="button" id="svgen" value="Save Changes"/></p>
</div>

<div id="stg">
<table  align='center' cellpadding='10'>
<tr>
<td>Stage No.</td><td><input id="stgid" value="1" /></td>
</tr>
<tr>
<td>Title</td><td><input type="text" size="40" id="stgtit" /></td>
</tr>
<tr>
<td>內容<a href="#" id="demostg">(See Effect)</a></td><td><textarea id="stgcont" rows="12" cols="70"></textarea></td>
</tr>
<tr>
<td>Answer</td><td><input type="text" id="stgans" /></td>
</tr>
<tr>
<td>Score</td><td><input type="text" id="stgscr" /></td>
</tr>
</table>
<p align="center"><input type="button" id="svstg" value="Save Changes"/></p>
</div>
<div id="nus">
<table  align='center' cellpadding='10'>
<tr>
<td>Username</td><td><input type="text" id="newu" /></td>
</tr><tr>
<td>Password</td><td><input type="text" id="newp" /></td>
</tr>
</table>
<p align="center"><input type="button" id="newus" value="Add User"/></p>

</div>
<div id="rank">
</div>
</div>
<div id="alert" title="System Message">

</div>
</body>
</html>
<?
}
?>