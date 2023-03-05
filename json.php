<?php
session_start();
include 'func.php';
include 'db_.php';

$chopen = mysql_fetch_array(mysql_query("SELECT * FROM orgen WHERE id=1"));

if ($chopen['data']=="open") {
$choptl = mysql_fetch_array(mysql_query("SELECT * FROM orgen WHERE id=2"));
$t = time();
if ($t > $choptl['ndata']) {
$open = 0;
mysql_query("UPDATE orgen SET data='close' WHERE id=1");
} else {
$open = 1;
}
}
 else {
$open = 0;
}
$goalstg = mysql_fetch_array(mysql_query("SELECT * FROM orgen WHERE info='goal_stg'"));
$goalstg = $goalstg['ndata'];
if ($_POST['e']=="log") {
$u = $_POST['u'];
$p = $_POST['p'];
$p = salt($p);
$query = mysql_query("SELECT * FROM oruser WHERE uid='$u' AND pwd='$p'");
	if (mysql_num_rows($query)!=1) {
	echo '
	{ "info":"Incorrect username or password！" }
	';
	} else {
	setcookie("uid",$u,time()+31536000);
	setcookie("pwd",$p,time()+31536000);
	echo '
	{ "info":"" }
	';

	}
}
elseif($_POST['e']=="logout") {
setcookie("uid",$u,time()-31536000);
setcookie("pwd",$p,time()-31536000);
echo '
{ "info":"Logged out successfully！" }
';
}
			elseif($_POST['e']=="9") {
				function checkvalid($x) {
				if (strlen($x) > 32) {
				return 32;
				} else {
					$search = array("+","-","*","/","(",")");
					$checkstr = str_replace($search,",",$x);
					$array = explode(",", $checkstr);
					$count = count($array);
					$flag = 1;
					for ($i=0;$i<=$count;$i++) {
					if (strlen($array[$i])>1) {
					$flag = 0;
					break;
					}
					}
					return $flag;
				}
			}
				if (checkvalid($_POST['i'])!=1) {
				echo '
				{ "info":"Incorrect format!" }
				';
				} else {
				$check = mysql_query("SELECT * FROM orgen WHERE data='stage9' AND id=".$_POST['i']);
				if (mysql_num_rows($check) ==1) {
				echo '
				{ "info":"The password is following hash: 34dc3540584cad560a45b71655f81425" }
				';
				} else {
				echo '
				{ "info":"Incorrect answer!" }
				';
				}
				
				}
			
			}
			elseif($_POST['e']=="19s") {
			$split = str_split("RRRRRRRRDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD");
			shuffle($split);
			$arr = array();
			
			for ($i=0;$i<=30;$i++) {
			for ($j=0;$j<=8;$j++) {
			$arr[$i][$j]=0;
			}
			}
			$arr[0][0] = 1;
			$x = 0;
			$y = 0;
			for ($i=0;$i<count($split);$i++) {
			if ($split[$i]=="D") {
			$x++;
			} else {
			$y++;
			}
			$arr[$x][$y]=1;
			}
			$str = '<table border=1>';
			for ($i=0;$i<=30;$i++) {
			$str .= '<tr style=font-size:0.1em;>';
			for ($j=0;$j<=8;$j++) {
			$str .= '<td>';
			$str .= $arr[$i][$j];
			$str .= '</td>';
			}
			$str .='</tr>';
			}
			$str .= '</table>';
			$_SESSION['t'] = time();
			$_SESSION['ans'] = implode("",$split);;
				echo '
				{ "info":"<div align=center>'.$str.'</div>" }
				';
			}
			elseif($_POST['e']=="19c") {
			$ans = $_POST['ans'];
			$time = time();
			if ($time-$_SESSION['t']>15) {
				echo '
				{ "info":"Overtime, try again！" }
				';
			} elseif ($ans!=$_SESSION['ans']) {
				echo '
				{ "info":"Incorrect answer！" }
				';
			} else {
				echo '
				{ "info":"The password is following hash: a5d8f3eece019a2beacf3b3dc5d33b30" }
				';
			}
			}
elseif($_POST['e']=="stgbtn") {
$u = $_POST['u'];
$p = $_POST['p'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='$u' AND pwd='$p'");
if (mysql_num_rows($query)!=1) {
	echo '
	{ "info":"" }
	';
} else {
$row = mysql_fetch_array($query);
$s = $row['stg'];
$scr = $row['score'];
$arr = str_split($s);
$j = 1;
$k = 0;
$str = '{ "info":"<div align=center>';
	for ($i=0;$i<=27;$i++) {
	$k++;
	$ii = $i+1;
		if ($arr[$i]=="0") {
		$str.= "<input type='button' id='s".$ii."' value='".$ii."' onclick='chid(this.value)' />";
		} elseif ($arr[$i]=="1") {
		$str.= "<input type='button' id='s".$ii."' value='".$ii."' class='ui-state-error' onclick='chid(this.value)' />";
		} else {
		$str.= "<input type='button' id='s".$ii."' value='".$ii."' disabled>";
		}
		if ($j==$k) {
		$k = 0;
		$j++;
		$str.= "<br />";
		}
	}
$str .= '</div>", "score":"'.$scr.'"}';
echo $str;
}
}
elseif($_POST['e']=="stg") {
if ($open ==0) {
	echo '
	{ "info":"The game is closed now!" }
	';
} else {
$u = $_POST['u'];
$p = $_POST['p'];
$stg = $_POST['stage'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='$u' AND pwd='$p'");
if (mysql_num_rows($query)!=1) {
	echo '
	{ "info":"Stage Initialization Error!" }
	';
} else {
$row = mysql_fetch_array($query);
$perm = $row['stg'];
if ($perm{$stg-1}!="N") {
$getstg = mysql_query("SELECT * FROM orstg WHERE id='".$stg."'");
$stage = mysql_fetch_array($getstg);
$tit = "Stage " . $stg. " - "  . $stage['title'];
$cont = $stage['content'];

	echo '
	{ "info":"", "tit":"'.$tit.'", 
	"stgid":"'.$stg.'",
	"cont":"'.$cont.'",
	"scr":"'.$stage['score'].'"
	}
	';
} else {
	echo '
	{ "info":"Stage Initialization Error!" }
	';
}
	
}
}
}
elseif($_POST['e']=="chk") {
if ($open ==0) {
	echo '
	{ "info":"The game is closed now!" }
	';
} else {
$u = $_POST['u'];
$p = $_POST['p'];
$stgid = $_POST['stage'];
$query = mysql_query("SELECT * FROM oruser WHERE uid='$u' AND pwd='$p'");
if (mysql_num_rows($query)!=1) {
	echo '
	{ "info":"Error!" }
	';
}  else {
$row = mysql_fetch_array($query);
$getstg = mysql_query("SELECT * FROM orstg WHERE id='".$stgid."'");
$stage = mysql_fetch_array($getstg);
$arr = str_split($row['stg']);
$corr = 0;
$pos = strpos($stage['ans'],';');
if ($pos===true) {
	if ($_POST['ans'] == $stage['ans']) {
	$corr = 1;
	}
} else {
$ans = explode(";",$stage['ans']);
	for($i=0;$i<count($ans);$i++) {
		if ($_POST['ans']== $ans[$i]) {
		$corr = 1;
		}
	}
}

if ($corr==1 && $arr[$stgid-1]=="0") {
$newscore = $row['score'] + $stage['score'];
$t = time();
$arr[$stgid-1] = 1;
if ($stgid==1) {
$arr[1] = 0;
$arr[2] = 0;
}
if ($stgid==2) {
$arr[3] = 0;
if ($arr[2]==1) {
$arr[4] = 0;
}
}
if ($stgid==3) {
$arr[5] = 0;
if ($arr[1]==1) {
$arr[4] = 0;
}
}
if ($stgid==4) {
$arr[6] = 0;
if ($arr[4]==1) {
$arr[7] = 0;
}
}
if ($stgid==5) {
if ($arr[3]==1) {
$arr[7] = 0;
}
if ($arr[5]==1) {
$arr[8] = 0;
}
}
if ($stgid==6) {
$arr[9] = 0;
if ($arr[4]==1) {
$arr[8] = 0;
}
}
if ($stgid==7) {
$arr[10] = 0;
if ($arr[7]==1) {
$arr[11] = 0;
}
}

if ($stgid==8) {
if ($arr[6]==1) {
$arr[11] = 0;
}
if ($arr[8]==1) {
$arr[12] = 0;
}
}

if ($stgid==9) {
if ($arr[7]==1) {
$arr[12] = 0;
}
if ($arr[9]==1) {
$arr[13] = 0;
}
}

if ($stgid==10) {
$arr[14] = 0;
if ($arr[8]==1) {
$arr[13] = 0;
}
}

if ($stgid==11) {
$arr[15] = 0;
if ($arr[11]==1) {
$arr[16] = 0;
}
}

if ($stgid==12) {
if ($arr[10]==1) {
$arr[16] = 0;
}
if ($arr[12]==1) {
$arr[17] = 0;
}
}

if ($stgid==13) {
if ($arr[11]==1) {
$arr[17] = 0;
}
if ($arr[13]==1) {
$arr[18] = 0;
}
}

if ($stgid==14) {
if ($arr[12]==1) {
$arr[18] = 0;
}
if ($arr[14]==1) {
$arr[19] = 0;
}
}

if ($stgid==15) {
$arr[20] = 0;
if ($arr[13]==1) {
$arr[19] = 0;
}
}

if ($stgid==16) {
$arr[21] = 0;
if ($arr[16]==1) {
$arr[22] = 0;
}
}

if ($stgid==17) {
if ($arr[15]==1) {
$arr[22] = 0;
}
if ($arr[17]==1) {
$arr[23] = 0;
}
}
if ($stgid==18) {
if ($arr[16]==1) {
$arr[23] = 0;
}
if ($arr[18]==1) {
$arr[24] = 0;
}
}
if ($stgid==19) {
if ($arr[17]==1) {
$arr[24] = 0;
}
if ($arr[19]==1) {
$arr[25] = 0;
}
}
if ($stgid==20) {
if ($arr[18]==1) {
$arr[25] = 0;
}
if ($arr[20]==1) {
$arr[26] = 0;
}
}
if ($stgid==21) {
if ($arr[19]==1) {
$arr[26] = 0;
}
$arr[27] = 0;
}
$newstg = implode($arr);
mysql_query("UPDATE oruser SET time_='$t',score='$newscore',stg='$newstg' WHERE uid='$u' AND pwd='$p'");


echo '
{ "info":"" }
';
} else {
	echo '
	{ "info":"Incorrect password！" }
	';
}
}
}
} 
elseif($_POST['e']=="rnk") {
$u = $_POST['u'];
$query = mysql_query("SELECT * FROM oruser WHERE uid!='admin' ORDER BY score DESC, time_ ASC");
$out = "";
$i = 1; 
while ($row = mysql_fetch_array($query)) {
	if ($row['uid']==$u) {
	$out.="<tr bgcolor='#222'>";
	} else {
	$out.="<tr>";
	}
$mb = "[".$row['mb']."]";
$out.="<td>#".$i."</td>";
$out.="<td>".$row['uid'].$mb."</td>";
$out.="<td>".$row['score']."</td>";
if ($row['time_']=="2147483647") {
$t = "-";
} else {
$t = date("Y-m-d H:i:s", $row['time_']);
}
$out.="<td>".$t."</td>";
$out.="</tr>";
$i++;
}
$out.="</table>";

echo '
{ "out":"'.$out.'" }
';
}
?>