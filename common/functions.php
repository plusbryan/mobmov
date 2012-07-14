<?php
putenv("TZ=America/Los_Angeles");
define('MINUTE',60);
define('HOUR',3600);
define('DAY',86400);
define('WEEK',604800);
define('MONTH',2592000);
define('YEAR',31536000);
$time_offset = 3 * HOUR;
$devhost = false;
$submitter_ip = $_SERVER['REMOTE_ADDR'];

// -- for request()
define('EMAIL','email');
define('REQUEST','REQUEST');
define('POST','POST');
define('GET','GET');
define('COOKIE','COOKIE');
define('SESSION','SESSION');
define('IntOnly','int');
define('SINGLE_RESULT',true);
// -----------

require (realpath(dirname(__FILE__)) . "/../../common/config.php");


// -- for regex
define('REGEX_EMAIL','^([a-zA-Z0-9])+([a-zA-Z0-9\.\\+=_-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$');
define('REGEX_URL','^www\.([a-z0-9]+\.){1,3}((com)|(edu)|(gov))$');
define('REGEX_TEXTUAL','^[[:alpha:]]+$');
define('REGEX_NUMERIC','^(-)?[[:digit:]]+$');
define('REGEX_NOSPECIALS','^[[:alnum:]]+$');
define('REGEX_ZIP','^[[:digit:]]{5}(-[[:digit:]]{4})?$');
// -----------



if (in_array($_SERVER["SERVER_ADDR"],$devhosts)){
	//$_SERVER['SERVER_NAME'] = DEVHOST;
	$devhost = true;
}

require (realpath(dirname(__FILE__)) . "/data.php");


// *************************************************************
// *************************************************************

function request($name,$method="REQUEST",$type="") {
	$safe="";
	$unsafe="";
	if ($method == POST) {
		if(isset($_POST[$name])) { 
			$unsafe = $_POST[$name];
		}
	} elseif ($method == GET) {
			if(isset($_GET[$name])) { 
				$unsafe = $_GET[$name];
			}
	} elseif ($method == COOKIE) {
		if(isset($_COOKIE[$name])) { 
			$unsafe = $_COOKIE[$name];
		}
	} elseif ($method == SESSION) {
		if(isset($_SESSION[$name])) { 
			$unsafe = $_SESSION[$name];
		}
	} else {
		if(isset($_REQUEST[$name])) { 
			$unsafe = $_REQUEST[$name];
		}
	}
	$safe = htmlentities($unsafe, ENT_COMPAT, 'UTF-8');
	if ($type) {
		settype($safe,$type);
	}
	return $safe;
}

// returns false if does not match format
// returns string if matches
function filterformat($type,$string) {
	if ($type == 'email') {
		$return = eregi(REGEX_EMAIL, $string);
	} elseif ($type == 'url') {
		$return = eregi(REGEX_URL, $string);
	} elseif ($type == 'textual') {
		$return = eregi(REGEX_TEXTUAL, $string);
	} elseif ($type == 'numeric') {
		$return = eregi(REGEX_NUMERIC, $string);
	} else {
		$return = false;
	}
	if ($return) {
		return $string;
	} else {
		return false;
	}
}

// queries db in sanitized manner
// expects: ($sql,$var1,$var2,$var3...)
// $sql format: %s = string (sprintf)
function mysql_query_safe() {
	db_connect();
	global $MODE_NODATA;
	
	$sql = array(func_get_arg(0));
	$num_args = func_num_args();
	// loop through remaining parameters
	for($i = 1 ; $i < $num_args; $i++) {
		$sql[]=mysql_real_escape_string(func_get_arg($i));
	}
	
	// call sprintf with parameters: $sql, $var1, $var2, etc...
	$safe_sql = call_user_func_array('sprintf',$sql);
	
	// determine if this sql statement updates stuff or not
	$sql_writing = false;
	if (!strstr($safe_sql,"SELECT")) {
		$sql_writing = true;
	}
	//print $safe_sql."<br>";
	if ($MODE_NODATA && $sql_writing) {
		print "NODATA MODE: sql query blocked: $safe_sql";
	} else {
		return mysql_query($safe_sql);
	}
}
function mysql_query_result($sql,$ttl=0) {
	if ($ttl>0) {
		$val = memcached(md5($sql));
		if ($val=='') {
			$result=mysql_query_safe($sql);
			$val = mysql_result($result,0,0);
			memcached(md5($sql),$val,$ttl);
		}
	} else {
		$result=mysql_query_safe($sql);
		$val = mysql_result($result,0,0);
	}
	return $val;
}

function memcached($key,$val='',$ttl=60) {
	if (class_exists(Memcache)) {
        $memcache = new Memcache;

        $memcache->connect('localhost', 11211);
        if ($val<>'') {
            $memcache->set($key,$val,0,$ttl);
            return true;
        } else {
            return $memcache->get($key);
        }
	}
}


function defaultto($value,$default) {
	return $value ? $value : $default;
}

function countMembers($chapter_id) {
	db_connect();
	$sql ="SELECT count(*) FROM mailinglist AS m CROSS JOIN mailinglist_chapters AS c USING (member_id) WHERE c.chapter_id = '$chapter_id'";
    $result = mysql_query($sql);
    $count = mysql_result ($result, 0,0);
    return $count;
}

// get movie name
function get_movie_name($id) {
	db_connect();
	$sql = "SELECT movie_title FROM showings_movies WHERE showing_movie_id ='$id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){$row = mysql_result($result,0,0);}
    return $row;
}



// get loc name
function get_location_name($id) {
	db_connect();
	$sql = "SELECT loc_name FROM chapters_locations WHERE chapter_location_id ='$id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){
    $row = mysql_result($result,0,0);
	}
    return $row;
}

// get loc chapter
function get_location_chapter($id) {
	db_connect();
	$sql = "SELECT chapter_id FROM chapters_locations WHERE chapter_location_id ='$id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){$row = mysql_result($result,0,0);}
    return $row;
}

// get coord_id of chapter
function get_chapter_driver_id($chapter_id) {
	db_connect();
	$sql = "SELECT coord_id FROM chapters WHERE chapter_id ='$chapter_id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){$row = mysql_result($result,0,0);}
    return $row;
}

// get coord_id of chapter
function get_chapter_driver_email($chapter_id) {
	$coord_id = get_chapter_driver_id($chapter_id);
	db_connect();
	$sql = "SELECT email FROM members WHERE member_id ='$coord_id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){$row = mysql_result($result,0,0);}
    return $row;
}

// get member id
function get_member_id($email) {
	db_connect();
	$sql = "SELECT member_id FROM mailinglist WHERE email ='$email'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){$row = mysql_result($result,0,0);}
    return $row;
}

// get chap name
// -- set name to report back entered name, not location
function get_chapter_name($id=0,$name=false) {
	db_connect();
    if ($id) {
    	$chapterid = " WHERE chapter_id ='$id'";
    }
	$sql = "SELECT title, city, state,country FROM chapters $chapterid";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)>0){$row = mysql_fetch_array($result);}
    if ($name) {
    	$return = $row['title'];
    	if ($row['city'] && $row['state']) {
    		$return .= " (" . $row['city'] . ", " . $row['state'] . ")";
    	}
    } else {
    	if ($row['state']) {
    		$return = $row['city'] . ", " . $row['state'];
        } else {
        	$return = $row['city'] . ", " . $row['country'];
        }
    }
    return $return;
}

// TRUNCATE
function truncate($str, $length, $trailing='...') {
	if (strlen($str) > $length && !$length==0) {
		// take off chars for the trailing
		$length -= strlen($trailing);
		// string exceeded length, truncate and add trailing dots
		$res = trim(substr($str,0,$length)).$trailing;
	} else {
		// string was already short enough, return the string
		$res = $str;
	}
	return $res;
}

// y to true
function ytotrue($into) {
	if ($into == "y" || $into == "Y" || $into == 1) {
    	$outto = true;
    } else {
    	$outto = false;
    }
	return $outto;
}

// std time
function sdatetime($datein) {
	$dateout = date("n/j/Y g:iA",$datein);
	return $dateout;
}
function stime($datein) {
	$dateout = date("g:iA",$datein);
	return $dateout;
}
function sdate($datein) {
	$dateout = date("n/j/Y",$datein);
	return $dateout;
}

// DATE FORMATTER 
// -- converts MySQL dates to US dates: MM/DD/YYYY
// -- if no date is input, output formatted current date
define('C_INCLUDE_TIME',true);
define('C_DEFAULT_TO_NOW',true);
function DBtoUSdate($inputDate = "",$default=true,$inctime=false) 
{
	global $time_offset;
	if ($inputDate == "0000-00-00" || $inputDate == "0000-00-00 00:00:00") { $inputDate = ""; }
	if (!$inputDate && !$default) {
		return "";
	} else {
	
		if (!$inputDate && $default) {
			$inputDate = time() - $time_offset;
		} else {
			$inputDate = strtotime($inputDate);
		}
		if ($inctime) {
			$outputDate = date("n/j/Y g:iA",$inputDate);
		} else {
			$outputDate = date("n/j/Y",$inputDate);
		}
	
		return $outputDate;
	}
}


// SELECTED
// -- shorthand for the oft-used selection choice of a select drop-down
function selected($saved_value, $iterated_value="") {
	if ($saved_value == $iterated_value) {
		return ' selected';
	} else {
		return '';
	}
}

// CHECKED
// -- shorthand for the oft-used selection choice of a checkbox
function checked($saved_value, $iterated_value="") {
	if ($iterated_value && $saved_value == $iterated_value) {
		echo " checked";
	}
}

// REDIRECT TO ANOTHER PAGE (with message)
// -- redirects to a new URL using meta tags
function go( $url, $delay = 0, $subject = "", $message="" ) { 
   require_once ("top.php");
   echo "<meta http-equiv='Refresh' content='".$delay."; url=".$url."'>";
   echo "<br><br><table  class=\"trans\" bgcolor=\"#336699\" cellspace=1><tr><td width=350 height=100 bgcolor=#e6e6e6><div valign=middle align=center><font size=\"4\"><b> ".$subject." </b></font><br><br>".$message." </div></td></tr></table></center>"; 
   require_once ("bot.php");
   die();
}

// GET FILE EXTENSION for uploaded files
function getFileExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return strtolower($ext);
}

// CAPITALIZE
function Capitalize($new_name) {
    $cap_name='';
    $started=0;
    $name = explode("-", $new_name);
    for ($i=0;$i<count($name);$i++) {
        if ($started == 1) { 
            //accounts for hypenated names
            $cap_name .= '-';
        } else {
            $started = 1;
        }
        $cap_name .= ucwords(strtolower($name[$i]));
    }
    //and return it.
    return $cap_name;
}

// RANDOM ID
function makeRandomID() { 
    $pass = "";
    $salt = "abchefghjkgwomnpqrtzxlmyt"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    while ($i <= 5) { 
        $num = rand() % 33; 
        $tmp = substr($salt, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    return $pass; 
}


// RANDOM PASSWORD
function makeRandomPassword() { 
    $pass = "";
    $salt = "abchefghjkgwomnpqrstABCFPZ0123456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($salt, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    return $pass; 
}

// RANDOM NUMBER
function RandomNumber($limit=10,$fromzero=false) { 
	srand((double)microtime()*1000000); 
	$num = rand() % $limit; 
	if ($fromzero) {
    return $num; 
    } else {
     return $num+1; 
    }
}

// RANDOM FROM ARRAY
function RandomArray($possiblevalues) { 
	if (is_array($possiblevalues)) {
		srand((double)microtime()*1000000); 
		$count = count($possiblevalues);
		$num = rand() % $count; 
    	return $possiblevalues[$num]; 
    } else {
    	return $possiblevalues; 
    }
}

// CHECK FORMAT
class checkformat {
	function email($test_string) {
		return eregi('^[a-zA-Z0-9_\+\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $test_string); 
	}
	function url($test_string) {
		return eregi('^www\.([a-z0-9]+\.){1,3}((com)|(edu)|(gov))$', $test_string);
	}
	function textual($test_string) {
		return eregi('^[[:alpha:]]+$', $test_string); 
	}
	function numeric($test_string) {
		return eregi('^(-)?[[:digit:]]+$', $test_string); 
	}
	function nospecials($test_string) {
		return eregi('^[[:alnum:]]+$', $test_string); 
	}
	function zip($test_string) {
		return ereg('^[[:digit:]]{5}(-[[:digit:]]{4})?$', $test_string); 
	}
}


function email($to,$subject,$body) {
	
	require_once('AmazonSESMailer.php');

	$mail = new AmazonSESMailer(SES_ID,SES_KEY);
    
    if (is_array($to)) {
		foreach ($to as $oneto) {
			$oneto = filterformat(EMAIL,$oneto);
            if ($oneto<>'') {
				$mail->AddAddress($oneto);
			}
		}
	} else {
		$mail->AddAddress($to);
	}
    
    $mail->SetFrom('info@mobmov.org');
    $mail->Subject = $subject;
    $mail->Body = $body;

	return (bool)$mail->Send();
}



// FORMATAS --------------
define('C_SHOWSYMBOL',true);
define('C_HIDESYMBOL',false);
class formatas {
	function number($unclean,$limitlen="",$limitlen2="") {
		$clean = ereg_replace ('[^0-9]+', '', $unclean); //^
		if ((!$limitlen && !$limitlen2) || ($limitlen && strlen($clean) == $limitlen) || ($limitlen2 && strlen($clean) == $limitlen2)) {
			return $clean;
		} else {
			return $unclean;
		}
	}
	function phone_number($unclean){
		$cleaner = formatas::number($unclean);
	    if (strlen($cleaner) == 10) { 
		    $sArea = substr($cleaner,0,3);
		    $sPrefix = substr($cleaner,3,3);
		    $sNumber = substr($cleaner,6,4);
		    $clean = "(".$sArea.") ".$sPrefix."-".$sNumber;
		    return $clean;
		} elseif (strlen($cleaner) == 12) { 
		    $sArea = substr($cleaner,0,2);
		    $sPrefix = substr($cleaner,2,4);
		    $sNumber = substr($cleaner,6,3);
		    $sNumber2 = substr($cleaner,9,3);
		    $clean = "+".$sArea." ".$sPrefix." ".$sNumber." ".$sNumber2;
		    return $clean;
	    } else {
	    	return $unclean;	
	    }
	} 
}


function forceSSL() {
    if( $_SERVER['SERVER_PORT'] == 80) {
                header('Location:https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.basename($_SERVER['PHP_SELF']));
            exit();
        }
}

function detectBrowser() {

    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') ) {
        $browser = 'mac';
    } else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') ) {
        $browser = 'firefox';
    } else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ) {
        $browser = 'ie';
    } else {
        $browser = 'other';
    }

    return $browser;

}


// *************************************************************
//      MYSQL
// *************************************************************

function db_connect($connect=true) {
    global $db;
    if ($connect && empty($db)) {
        $db = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) OR die('Temporary database server error: '.DB_HOST);
        @mysql_select_db(DB_NAME) OR die('Temporary server error.');
    }
}

// makes strings mysql safe
function sanitize(&$string) {
        // note: mysql_real_escape requires a connection
        $string = mysql_escape_string($string);
}

function mysql_safe($string) {
        return mysql_escape_string($string);
}

function mysql_fetch_string_helper($sql){
        db_connect();
        $result = mysql_query($sql);
        if (mysql_num_rows($result)==1) {
                return mysql_result($result,0,0);
        }
}

function mysql_fetch_string($table,$field,$where="") {
        if ($where) { $where = "WHERE $where"; }
        $sql = "SELECT $field FROM $table $where LIMIT 1";
        return mysql_fetch_string_helper($sql);
}

function mysql_fetch_global($field) {
        $sql = "SELECT value FROM globals WHERE field='$field' LIMIT 1";
        return mysql_fetch_string_helper($sql);
}
// *************************************************************


define('TOP',1);
define('BOTTOM',0);
function roundedbox($start=TOP,$color="dddddd",$corner_width="8",$width="100%",$height=""){
//add px to any non-percents
if (substr($width,-1) !== "%") {
	$width=$width."px";
}
if (!height) { $height = $width; }
if ($start) {
$return = <<<BOX
<div style="position:relative;background-color:#$color;padding:12px;width:$width;height:$height;-moz-border-radius: {$corner_width}px;border-radius: {$corner_width}px;">
BOX;
} else {
$return = <<<BOX
</div>
BOX;
}
return $return;
}

