<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");

if(!$global_islogged){
	header("Location: index.php");
	exit;
}

$DEBUG = false;

$skipid = $_POST['skipid'];
$skip = $_POST['skip'];
$table = $_POST['table'];
if (!$table) {
	print "Please provide a table name - contact the webmaster for help";
    die();
}
if ($skip) {
	$skipa = split(",",$skip);
} else {
	$skipa = array();
}
$skipa[]="submit";
$skipa[]="table";
$skipa[]="id";
$skipa[]="skipid";
$skipa[]="return";
$skipa[]="old_password";
$skipa[]="test";
$skipa[]=$skipid;

$gothru = array();
while(list($key, $val) = each($_POST)){
	$key = "post_".strtolower($key);
	$$key = $val;
    if ($DEBUG) print $key."=".$val."<br>";
    $gothru[] = $key;
}
//import_request_variables("P", "post_");

foreach ($gothru as $key) {

	$val = $$key;
	if (!in_array(str_replace("post_","",$key),$skipa) && $key != "post_skip") {
    	
    	
        //print $key."<br>";
    	// check for special formatting
         if (substr($key,5,1) == "@") {		// datetime
			$key = str_replace("@","",$key);	// remove front char
			$val = "FROM_UNIXTIME(".strtotime($val).")";
			if ($sqt && $key) { $sqt .= ","; }
            $sqt .=  str_replace("post_","",$key). " = $val";
		
		} elseif (substr($key,-5,5) == "_date") {		// datetime
			// check for time field - name as '!datetime-time'
			$key = str_replace("post_","",$key);
			$key = str_replace("_date","",$key);	// remove front char
			$special_time_name = 'post_' . $key . '_time!';
			//print $special_time_name; 
			if ($$special_time_name) { // add time if it exists
				//print "moding".$$special_time_name;
				$val = $val . " " . $$special_time_name;
			}
			
			$val = "FROM_UNIXTIME(".strtotime($val).")";
            
			//print $key." --" . $val ."<br>";
			if ($sqt && $key) { $sqt .= ","; }
            $sqt .=  str_replace("post_","",$key). " = $val";
          } elseif (substr($key,5,1) == "*") {		// password
          	if ($post_old_password != $val) {
				$val = "OLD_PASSWORD('$val')";
                $key = str_replace("*","",$key);	// remove front char
                if ($sqt && $key) { $sqt .= ","; }
            	$sqt .= str_replace("post_","",$key). " = $val"; 
                //print "password! $old_password  and $val";
            } 
		  } elseif (substr($key,-1,1) == "!") {		// ignore
          	//ignoring names with ! at end
         } else {
		 	if ($sqt && $key) { $sqt .= ","; }
            $sqt .= str_replace("post_","",$key). " = '".addslashes($val)."'";
         }
         
	}
    $return = str_replace("%$key%",urlencode($val),$return);
}

db_connect();
if ($_POST[$skipid]) {
	$action = "UPDATE";
    $fixedskipid = "post_".$skipid;
    $where = "WHERE $skipid='".$$fixedskipid."'";
} else {
   $action = "INSERT INTO";
}
$sqt = str_replace(",,",",",$sqt);
$sql = "$action $table SET $sqt $where";
if ($DEBUG) print $sql."<br>";

$result = mysql_query($sql);
$insert_id = mysql_insert_id();

if (!$result) {
	print "The save did not work:<br><br>$sql";
    die();
}
if (!$post_return) {
	$post_return = getenv('HTTP_REFERER');
} else {
    $post_return = str_replace("%id%",urlencode($insert_id),$post_return);
}
if ($DEBUG) print "return: ".$post_return;
if ($DEBUG) die();
header("Location: $post_return");
?>