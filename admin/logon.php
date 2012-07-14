<?
require_once("inc_functions.php");

$logon = $_REQUEST['logon'];
$password = $_REQUEST['password'];

db_connect();
$sql = "SELECT * FROM members WHERE (email = '$logon' OR username = '$logon') AND password=OLD_PASSWORD('$password')";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result)){ 
	foreach($row AS $key => $val){ 
		$key = strtolower($key);
		$$key = $val; 
	}
}

if ($member_id) {
    $superuser = ytotrue($superuser);
    
    if ($superuser) {
    	$sql = "SELECT chapter_id FROM chapters";
    } else {
    	$sql = "SELECT chapter_id FROM chapters WHERE coord_id = '$member_id'";
    }
    $result = mysql_query($sql);
    
    while($row = mysql_fetch_array($result)){ 
    	if ($allchapters) { 
        	$allchapters .= ","; 
        }
    	$allchapters .= $row['chapter_id'];
        $onechap = $row['chapter_id'];
    }
    
    // check "other" drivers info
    if (!$superuser) {
    	$sql = "SELECT chapter_id FROM chapters_drivers WHERE driver_id = '$member_id'";
    }
	while($row = mysql_fetch_array($result)){ 
    	if ($allchapters) { 
        	$allchapters .= ","; 
        }
    	$allchapters .= $row['chapter_id'];
        $onechap = $row['chapter_id'];
    }
}

$access = ytotrue($access);

if($access){
	$_SESSION['islogged'] = true;
    $_SESSION['superuser'] = $superuser;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['chapters'] = $allchapters;
    $_SESSION['chapter_id'] = $onechap;
  
	header('Location: desktop.php');
	exit;
}else{
	$_SESSION['islogged'] = false;
	header('Location: index.php?err=1');
	exit;
}
?>