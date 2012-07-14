<?
//*************************************************//
//            Project: The Body Positive	       //
//               Date: October, 2005			   //
// Author: Amila Tennakoon <amila@electricfox.com> //
// Manager: Bryan Kennedy <bryan@@electricfox.com> //
//*************************************************//
require_once("inc_functions.php");

if(!$global_islogged){
	header("Location: index.php");
	exit;
}



while(list($key, $val) = each($_GET)){
	$key = strtolower($key);
	$$key = $val;
}

db_connect();
$sql = "DELETE FROM photos WHERE record_id = $record_id";
$result = mysql_query($sql);

$photo_file = "../photos/$page_id-$record_id.jpg";
if(file_exists($photo_file)) @unlink($photo_file);

header("Location: doc-edit.php?record_id=$page_id&err=0");
?>