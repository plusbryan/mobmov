<?
//*************************************************//
// MOBMOV
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
$sql = "DELETE FROM $table WHERE $col = '$id'";
if ($col2 && $id2) {
	$sql .= " AND $col2 = '$id2'";
}
//print $sql;
$result = mysql_query($sql);

if (!$return) {
	$return = getenv('HTTP_REFERER');
}
header("Location: $return");
?>
