<?php
session_start();
include 'func.php';
include 'db_.php';
if (isset($_COOKIE['uid']) && isset($_COOKIE['pwd'])) {
$uid = $_COOKIE['uid'];
$pwd = $_COOKIE['pwd'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='$uid' AND pwd='$pwd'");
if (mysql_num_rows($query)!=1) {
header("location:index.php");
exit();
} else {
}
} else {
header("location:index.php");
exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>電腦學會 - 隊際網上解謎遊戲比賽</title>
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
	$( "input[type='text'],input[type='password'],textarea" ).addClass("ui-widget ui-widget-content ui-corner-all");
	$("#subans").click(
	function() {
	$.post("json.php",
	{e:'chk',u:$.cookie("uid"),p:$.cookie("pwd"),ans:$("#password").val(),stage:$("#go2stg").val()},
	function(resp) {
	if (resp.info=="") {
	popup("你的答案正確！");
	$("#pg-2").click();
	} else{
	popup(resp.info);
	}
	},
	"json"
	);
	
	$("#password").val("");
	}
	);
	$("#pg-5").click(function() {
	$.post("json.php",
	{e:'rnk',u:$.cookie("uid")},
	function(resp) {
	var out = "<table border='1' align='center' cellpadding='10' bordercolor='#CCC' width='700'><tr><td>名次</td><td>隊編號[隊員]</td><td>分數</td><td>最後破關時間</td></tr>";
	$("#rank").html(out+resp.out);
	},
	"json"
	);
	});
	$("#pg-6").click(function() {
	$.post("json.php",
	{e:'logout'},
	function(resp) {
	popup(resp.info);
	setTimeout(function(){window.location.replace("index.php")},800);
	},
	"json"
	);
	});
	$("#pg-2").click(
	function() {
	$("#stgcont").hide();
	$("#stgtit").hide();
	$("#subpwd").hide();
	$("#hr").hide();
	$("#stgscrcol").hide();
	$("#instr").fadeIn();
	$("#stgbtn").fadeIn();
	$("#scrcol").fadeIn();
	$.post("json.php",
	{e:'stgbtn',u:$.cookie("uid"),p:$.cookie("pwd")},
	function(resp) {
	if (resp.info=="") {
		popup("Initialization Error!");
	} else {
		$("#stgbtn").html(resp.info);
		$("#score").text(resp.score);
		$( "input:button" ).button();
		$( "input[type='text'],input[type='password'],textarea" ).addClass("ui-widget ui-widget-content ui-corner-all");
		var array = new Array();
		for (stgval=1;stgval<=28;stgval++){
		id = stgval.toString();
		stgid = "#s".concat(id);
			$(stgid).click(
				function() {
				$.post("json.php",
				{e:'stg',u:$.cookie("uid"),p:$.cookie("pwd"),stage:$("#go2stg").val()},
				function(resp) {
				if (resp.info=="") {
				$("#stgtit").html(resp.tit);
				$("#stgscr").text(resp.scr);
				$("#stgcont").html(resp.cont);
				$( "input:button" ).button();
				$( "input[type='text'],textarea" ).addClass("ui-widget ui-widget-content ui-corner-all");
				$("#instr").fadeOut(300);
				$("#stgbtn").fadeOut(300);
				$("#scrcol").fadeOut(300);
				$("#stgcont").delay(300).fadeIn(300);
				$("#stgtit").delay(300).fadeIn(300);
				$("#subpwd").delay(300).fadeIn(300);
				$("#hr").delay(300).fadeIn(300);
				$("#stgscrcol").delay(300).fadeIn(300);
				if (resp.stgid=="28") {
	$("#hide19").click(
	function() { $("#m19").slideToggle();});
	$("#but19").click(
	function() {
	$.post("json.php",
	{e:'19s'},
	function(res) {
	if ($("#count").val()>0) {
	popup("前次任務尚未終結！");
	} else {
	popup(res.info);
	function timing(k) {
	$("#count").val(k);
	$("#area19").text("你尚有 "+$("#count").val()+" 秒完成任務。");
	if($("#count").val()>0) {
	$("#count").val($("#count").val() - 1);
	setTimeout(function(){timing($("#count").val())},1000);
	}
	}
	}
	timing(15);
	},
	"json"
	);
	});
	$("#sub19").click(
	function() {
	$.post("json.php",
	{e:'19c',ans:$("#inp19").val()},
	function(res) {
	popup(res.info);
	},
	"json"
	);
	});
	}
	if (resp.stgid=="19") {
	alert(resp.stgid);
	$("#but9").click(
	function() {
	$.post("json.php",
	{e:'9',i:$("#inp9").val()},
	function(res) {
	popup(res.info);
	},
	"json"
	);
	});
	}
				} else {
					popup(resp.info);
				}
				},
				"json"
				);
				}
			);
		}
	}
	},"json"
	);
	});

	/*$("#pg-2").click(
	function() {
	$.post("json.php",
	{e:'stg',u:$.cookie("uid"),p:$.cookie("pwd")},
	function(resp) {
	if (resp.info=="") {
	$("#stgtit").text(resp.tit);
	$("#stgcont").html(resp.cont);
	$( "input:button" ).button();
	$( "input[type='text'],input[type='password'],textarea" ).addClass("ui-widget ui-widget-content ui-corner-all");
	if (resp.stgid=="19") {
	$("#hide19").click(
	function() { $("#m19").slideToggle();});
	$("#but19").click(
	function() {
	$.post("json.php",
	{e:'19s'},
	function(res) {
	if ($("#count").val()>0) {
	popup("前次任務尚未終結！");
	} else {
	popup(res.info);
	function timing(k) {
	$("#count").val(k);
	$("#area19").text("你尚有 "+$("#count").val()+" 秒完成任務。");
	if($("#count").val()>0) {
	$("#count").val($("#count").val() - 1);
	setTimeout(function(){timing($("#count").val())},1000);
	}
	}
	}
	timing(15);
	},
	"json"
	);
	});
	$("#sub19").click(
	function() {
	$.post("json.php",
	{e:'19c',ans:$("#inp19").val()},
	function(res) {
	popup(res.info);
	},
	"json"
	);
	});
	}
	if (resp.stgid=="9") {
	$("#but9").click(
	function() {
	$.post("json.php",
	{e:'9',i:$("#inp9").val()},
	function(res) {
	popup(res.info);
	},
	"json"
	);
	});
	}
	} else if (resp.info=="g") {
	$("#subpwd").html("");
	$("#stgtit").html(resp.tit);
	$("#stgcont").html(resp.cont);
	}　else{
	popup(resp.info);
	}
	},
	"json"
	);
	});*/

	<? if (isset($_SESSION['msg'])) { echo "popup('".$_SESSION['msg']."');"; } ?>
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
		margin: 5px;
		color:white;
		font-size:14px;
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
<li><a href="#homepage" id="pg-1">開始頁</a></li>
<li><a href="#stg" id="pg-2">關卡</a></li>
<li><a href="#rules" id="pg-3">規則</a></li>
<li><a href="#help" id="pg-4">需要協助?</a></li>
<li><a href="#rank" id="pg-5">現時排名</a></li>
<li><a href="#logout" id="pg-6">登出</a></li>
</ul>
<div id="homepage">
<p>你好，<? echo $_COOKIE['uid'];?>！</p>
<p>歡迎來到由電腦學會主辦的隊際網上解謎遊戲比賽！<br />
顧名思義，各位同學需透過網頁中的各種線索解謎，取得答案以破關；<br />
此外，同學們或需要在網上搜尋資料，或相關的方法破關。<br>
</p><br />
<p style="font-size:20px"><b>請各位先按上面的按鈕參閱規則！</b></p>
<div align="center"><img src="images/logo.png" width="360" height="375" /></div>

</div>
<div id="rules">
<p>以下是本比賽的規則：</p><br />
<ul><li>本比賽共有 28 個關卡。</li></ul>
<ul><li>所有關卡的密碼應只包含細楷英文字母及數字，沒有空格和符號<br />
(例如若答案是"A's B-day", 密碼便是"asbday")。</li></ul>
<ul><li>在關卡頁面上，善用「右鍵->檢測元素」及「反白」，或可能讓你取得更多提示。</li></ul>
<ul><li>有時，你需要到 <a href="http://www.google.com/" target="_blank">Google</a> 或是 <a href="http://en.wikipedia.org/wiki/Main_Page" target="_blank">Wikipedia</a> 搜索才可獲得相關資訊過關。</li></ul>
<ul><li>本比賽限時1小時15分鐘(或有更改，請按工作人員指示)。</li></ul>
<ul><li>於限時之內，最快地取得最高分的一隊即為勝利者。</li></ul>
<ul><li>本比賽設冠、亞、季軍。</li></ul>
<ul><li>請所有參賽者在離開前交你的學生編號(Exxxx)予工作人員，以便記錄OLE之用。</li></ul>
</div>
<div id="stg">
<p id="scrcol"><span style="font-size:20px;padding:5px;color:#75abff;">Your Score:&nbsp;</span><b id="score" style="font-size:20px;"></b></p>
<div id="stgbtn">
</div>
<div id="instr">
<ol>
<li>請按以上的按鈕進入關卡。</li>
<li>按鍵為灰色的關卡代表尚未完成，綠色代表已完成，黑色則代表你未有權限進入該關。</li>
<li>要取得進入某關卡的權限，必先全部完成在其上方的一個或兩個關卡（按情況而定）。</li>
<li>在愈下方的關卡分數愈高，但難度亦相對高。</li>
</ol>
<input type="hidden" id="go2stg" value="" />
</div>
<div style="font-size:20px;padding:5px;color:#75abff;" id="stgtit"></div>
<div id="hr"><hr color="#096ac8" size="1"/></div>
<p id="stgscrcol"><u><i><span style="font-size:16px;">完成此關可得分:&nbsp;</span><b id="stgscr" style="font-size:16px;"></b></i></u></p>
<div style="font-size:15px;padding:35px;" id="stgcont">
</div>

<div id="subpwd"><p align="center"><input type="text" id="password" /> <input type="button" id="subans" value="輸入密碼"/></p></div>
</div>
<div id="help">
<p>如有任何疑問，歡迎向工作人員查詢 :)!</p>
</div>
<div id="rank">

</div>
</div>
<div id="alert" title="系統訊息">
</div>
</body>
</html>