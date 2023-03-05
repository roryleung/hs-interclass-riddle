<?php
function vaild_email($str)
{
if  (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) {
$rs = "TRUE";
}
else {
$rs ="FALSE";
}
return $rs;
}
function random_string($type,$len)
{					
	switch($type)
	{
		case 'alnum'	:
		case 'numeric'	:
		case 'nozero'	:
		
				switch ($type)
				{
					case 'alnum'	  :	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
						break;
					case 'numeric'	  :	$pool = '0123456789';
						break;
					case 'nozero'	  :	$pool = '123456789';
						break;
				}

				$str = '';
				for ($i=0; $i < $len; $i++)
				{
					$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
				}
				return $str;
		  break;
		case 'unique' : return md5(mt_rand());
		  break;
	}
}
function getbrowser() {
   $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $ub = ''; 
    if(preg_match('/MSIE/i',$u_agent)) 
    { 
        $ub = "Internet Explorer"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $ub = "Mozilla Firefox"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $ub = "Apple Safari"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $ub = "Google Chrome"; 
    } 
    elseif(preg_match('/Flock/i',$u_agent)) 
    { 
        $ub = "Flock"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $ub = "Opera"; 
    } 
    else
    {
        $ub = "Unclassified Broswer";
    }
	echo $ub;
}
function antiexp($str) {
	$string = $str;
	$exp = array('"', "'", "<", ">", "{", "}", "\\");
	$anti = array("&quot;", "&#39;", "&lt;", "&gt;", "&#123;", "&#125;", "&#92;");
	$newstr = str_replace($exp, $anti, $string);
	return $newstr;
}
date_default_timezone_set('Asia/Hong_Kong');
function ch_blank($str) {
	if (preg_match('/^[a-zA-Z0-9]+$/',$str)) {
		$valid=1;
	} else {
		$valid=0;
	}
	return $valid;
}
function salt($str) {
	$str = md5(sha1($str));
	$str = hash('haval160,4', $str);
	$str = hash('gost', $str);
	$str = md5($str);
	return $str;
}
if (isset($_GET['salt'])) {
echo $_GET['salt'];
echo '<br>';
echo salt($_GET['salt']);
}
?>
