<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");
if(!$global_islogged){
	header("Location: index.php");
	exit;
}

if ($global_superuser) {

db_connect();

	$sql = "SELECT * FROM mailinglist_chapters WHERE chapter_id =1";
	$result = mysql_query($sql);
	$count=0;
    while($row = mysql_fetch_array($result)){ 
		$member_id = $row['member_id'];
		$sql = "INSERT INTO mailinglist_chapters SET chapter_id = 195,member_id=$member_id";
		mysql_unbuffered_query($sql);
		$count++;
    }
	
print "copied $count members";
} ?>