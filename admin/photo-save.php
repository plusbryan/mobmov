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



while(list($key, $val) = each($_POST)){
	$key = strtolower($key);
	$$key = $val;
}

db_connect();

$sql = "INSERT INTO photos (page_id,caption,date_updated,alignment) VALUES ($record_id,'$caption',now(),'$alignment')";
$result = mysql_query($sql);
$photo_id = mysql_insert_id();

$photo_up = $_FILES['page_photo']['tmp_name'];
$photo_cp = "../photos/$record_id-$photo_id.jpg";

if(file_exists($photo_up)) move_uploaded_file($photo_up,$photo_cp);

header("Location: doc-edit.php?record_id=$record_id&err=0");
?>