<?
error_reporting(E_ALL ^ E_NOTICE);

//if ($_server['SERVER_ADDR'] == "72.3.245.204") {
	//define('DEVMODE',false);
//}
require_once($_SERVER['DOCUMENT_ROOT']."/mobmov.org/common/functions.php");

// ***************************************************
// ***************************************************
// BETALIST REMINDER
// ***************************************************
//		

// ***************************************************
// ***************************************************

$enable = true;

function cleanList($list){
	db_connect();
	$sql = "SELECT count(email) as ct, email FROM $list GROUP BY email";
	echo "\n".$sql;
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$email = $row['email'];
		// check for duplicates
		if($row['ct'] > 1) {
			// remove duplicates
			$newsql = "DELETE FROM $list WHERE email = '".addslashes($email)."' LIMIT ".($row['ct']-1);
			echo "\n".$newsql;
			mysql_query($newsql);
		}
		// check for bad email
		if (!filterformat(EMAIL,$email)) {
			// try to fix it
			$good_email = trim($email);
			// minor mistype repairs
			if (!filterformat(EMAIL,$good_email)) {
				$bad = array(",", " ");
				$good = array(".", "+");
				$good_email = str_replace($bad,$good,$email);
			}
			// advanced repairs
			if (!filterformat(EMAIL,$good_email)) {
				$bad = array("2");
				$good = array("@");
				$good_email = str_replace($bad,$good,$email);
			}
			
			if (!filterformat(EMAIL,$good_email)) {
				$newsql = "DELETE FROM $list WHERE email = '".addslashes($email)."' LIMIT 1";
                
				mysql_query($newsql);
				echo "\n".$newsql;
			} else {
			// looks like we fixed the email, let's update it!
				$newsql = "UPDATE $list SET email='$good_email' WHERE email = '$email' LIMIT 1";
                
				mysql_query($newsql);
            	echo "\n$newsql";
			}
		}
	}
}

if ($enable) {
	cleanList("mailinglist");
	//cleanList("members");
    
    // mailinglist_chapters
    // loop through mailinglist_chapters and delete all from expired members
    $sql = "SELECT c.*,m.member_id as inlist,cc.chapter_id as chapterexists FROM mailinglist_chapters c LEFT JOIN mailinglist m USING (member_id) LEFT JOIN chapters cc USING (chapter_id)";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
    	$link_id = $row['link_id'];
        $inlist = $row['inlist']; // member_id from mailinglist
        $chapterexists = $row['chapterexists'];
        $member_id = $row['member_id'];
        $chapter_id = $row['chapter_id'];
        
        // if either user account or chapter has been removed, remove the link
        if (!$inlist || !$chapterexists) {
        	$sql = "DELETE FROM mailinglist_chapters WHERE link_id='$link_id' LIMIT 1";
            mysql_query($sql);
            echo "\n$sql";
        }
    }
    
    // members
    // loop through members and remove anyone without chapters
    $sql = "SELECT m.member_id,c.chapter_id AS chapterexists FROM members m LEFT JOIN chapters c ON coord_id = member_id";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
    	$member_id = $row['member_id'];
        $chapterexists = $row['chapterexists'];
        
        // if either user account or chapter has been removed, remove the link
        if (!$chapterexists) {
        	$sql = "DELETE FROM members WHERE member_id='$member_id' LIMIT 1";
            mysql_query($sql);
            echo "\n$sql";
        }
    }
    
	print "\n\ndone";
}
?>
