<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("../inc_functions.php");

if(!$global_islogged){
	header("Location: index.php");
	exit;
}
db_connect();	

//require_once('zipcode.class.php');      // zip code class

$sql="SELECT chapter_id, city, state FROM chapters WHERE zip=''";
$result = mysql_query($sql);
//print $sql;

while ($row = mysql_fetch_assoc($result)) {
	$sql="SELECT zip_code FROM zip_code WHERE state_prefix='".$row['state']."' AND city='".$row['city']."'";
	$result2 = mysql_query($sql);
	$zip = @mysql_result($result2,0,0);
	//print $sql;
	
	if ($zip<>''){
	$sql="UPDATE chapters SET zip='".$zip."' WHERE chapter_id='".$row['chapter_id']."' LIMIT 1";
	//print $sql;
	
	mysql_query($sql);
	print "done $zip<br/>";
	}

}