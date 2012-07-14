<?php
print "Load\n";
ini_set('max_execution_time',0);
set_time_limit(0);
$max_sends = 200;
@include("/var/www/mobmov/mobmov.org/common/functions.php");
@include("/var/www/mobmov.org/common/functions.php");
print "/Load\n";

// LOCKFILE
// check for lockfile - make sure we're not duplicating processes
$lockfile = "/tmp/mobmov_mailer_lockfile";
if (file_exists($lockfile)) {
    // email the admin about it
    email("info@mobmov.org","Lockfile found!","$lockfile exists, exiting");
    die("Mailer locked! Remove lockfile from $lockfile");
} else {
	$fp = fopen($lockfile, 'a+');		//create empty file
	fclose($fp);
}


db_connect();
$sql = "SELECT * FROM mailings WHERE send_to != '' AND datesend < NOW() LIMIT 1";
$result = mysql_query($sql);

$total_count = 0;

if ($result && mysql_num_rows($result)>0){
	
    // EACH MAILER
	while($row = mysql_fetch_array($result)){
        $mailing_count = 0;
	  
	    // check if it's just a comma and delete it
        if ($row['send_to'] == ',') {
            $sql_rem = "UPDATE mailings SET send_to = '' WHERE mailing_id = '{$row['mailing_id']}'";
            mysql_query($sql_rem);
            continue;
        }
	  
		$sending_to = split(",",stripslashes($row['send_to']));

	    foreach ($sending_to as $email) {
            $original_email = $email;
	    	$email = filterformat(EMAIL,$email);
	        if ($mailing_count < $max_sends) { 
	        	if ($email) {   // valid email?
                    if (!strpos($row['sent_to'],$email)) {  // make sure this email didn't already go to this person

                        $body_send = stripslashes($row['body']);
                        $remove_from_list = "\n\nUnsubscribe from this list:\r\nhttp://mobmov.org/?cha=".$row['chapter_id']."&remv=".$email;

                        $body_send = str_replace("%email%",$email,$body_send);
                        $body_send = str_replace("%rsvp%","http://mobmov.org/?rsvp={$row['showing_id']}&email=$email",$body_send);
                        $body_send = strip_tags($body_send.$remove_from_list);
                        $subject = stripslashes($row['subject']);

                        print "\nsend to: $email";

                        if (PRODUCTION) {
                            email($email,$subject,$body_send);
                        }

                        $mailing_count++;
                        $total_count++;

                        // remove this person from the mailing
                        $row['send_to'] = str_replace($email,"",$row['send_to']);
                        $row['send_to'] = str_replace(",,","",$row['send_to']);
                        $row['send_to'] = str_replace(" ","",$row['send_to']);
                        $row['sent_to'] .= ",".$email;
                        if ($row['send_to'] == ",") { $row['send_to'] = ""; }
                        $sql_rem = "UPDATE mailings SET datesent=NOW(), send_to = '".addslashes($row['send_to'])."', sent_to = '".addslashes($row['sent_to'])."' WHERE mailing_id = '".$row['mailing_id']."'";
                        mysql_query($sql_rem);

                    } else {
                        print "\n-User already in sent to list! $email";
                    }

	            // bad e-mail address in file, remove from mailing (maybe also from list??)
	    		} else {
                    if ($original_email) {
                        $row['send_to'] = str_replace($original_email,'',$row['send_to']);
                        $row['send_to'] = str_replace(',,',',',$row['send_to']);
                        $row['send_to'] = str_replace(',,',',',$row['send_to']);
                        $row['send_to'] = str_replace(' ','',$row['send_to']);
                        $sql_rem = "UPDATE mailings SET send_to = '".addslashes($row['send_to'])."' WHERE mailing_id = '".$row['mailing_id']."'";
                        mysql_query($sql_rem);

                        $sql_rem = "DELETE FROM mailinglist WHERE email = '".addslashes($original_email)."'";
                        mysql_query($sql_rem);

                        print "\n-Removed $original_email from list";
                    } else {
                        print "\n-Bad/blank email";
                    }
	            } 
	        } else {
                print "\n+ Max sends reached! ($max_sends)";
                break;
            }
	    }
	    
	    print "\n\nMailing id ".$row['mailing_id']." finished this run. Sent to: ".$mailing_count;
        
		if ($mailing_count > 0) {
	        $to = "info@mobmov.org";
	    	$subject = "MAILER SENT: {$row['subject']}";
	    	$body = "Sent mailing id {$row['mailing_id']} to: $mailing_count emails this template:\n\n {$row['body']}";
	        email($to,$subject,$body);
		}
        
	} // each mailer
	mysql_free_result($result);
}

unlink($lockfile);

print "\n\nEND sent to count: $total_count\n\n";