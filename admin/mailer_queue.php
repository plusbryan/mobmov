<?
require('inc_functions.php');

// this mailer should
// 1) look in showings table to find any upcoming shows and get coordinates
// 2) find members within those coordinates
// 3) see if we've mailed them about this show already: mail_history
// 4) if not, queue up the message

// save message in message table
$sql = "SELECT * FROM showings WHERE showtime>NOW() AND stillon=1 AND lat<>'' AND lon<>''";
$showings = mysql_query_safe($sql);
	
while ($showing = mysql_fetch_assoc($showings)) {
	echo "try:".$showing['showing_id'];
	
	// select whom has already gotten a mail so we can exclude them
	$sql = "SELECT GROUP_CONCAT(mailinglist_id) FROM mail_history WHERE showing_id='%s'";
	$exresult = mysql_query_safe($sql,$showing['showing_id']);
	$excluded = @mysql_result($exresult,0,0);
	if ($excluded<>'') {$excluded = " AND member_id NOT IN($excluded) ";}
	mysql_free_result($exresult);
	
	// get emails from list
	// find people WITHIN COORD radius
	$lat = $showing['lat'];
	$lon = $showing['lon'];

	$sql = "SELECT member_id,((ACOS(SIN($lat * PI() / 180) * SIN(lat * PI() / 180) + COS($lat * PI() / 180) * COS(lat * PI() / 180) * COS(($lon - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance FROM mailinglist WHERE possible_bad_email=0 $excluded  HAVING distance<='10' ORDER BY distance ASC";
	print $sql;
	$members = mysql_query_safe($sql);
	$subcount=0;
	if ($members && mysql_num_rows($members)>0) {
	  while ($member = mysql_fetch_assoc($members)) {	
		  // loop through emails and ADD TO QUEUE
		  $sql = "INSERT DELAYED INTO mail_queue SET showing_id='%s',mailinglist_id='%s'";
		  mysql_query_safe($sql,$showing['showing_id'],$member['member_id']);
		  
		  // sender script should add to mail_history list
  
		  $subcount++;
	  }
	  
	  mysql_free_result($members);
	}

	
	
	echo "$subcount subscribers messages queued up for sending";
	
	
	// set this message as started
	//$sql = "UPDATE mailinglist_messages SET dtStarted=NOW() WHERE id='%s'";
	//mysql_query_safe($sql,$message['id']);
	
	
}
mysql_free_result($showings);

echo "done";
?>