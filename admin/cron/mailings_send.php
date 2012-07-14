#!/usr/bin/php
<?
ini_set('max_execution_time',0);
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", true);
$max_sends = 120;
require($_SERVER['DOCUMENT_ROOT'].'/mobmov.org/common/functions.php');

$return="";

// MAILER
// TEST DOES NOT WORK -- WARNING, MUST FIX BEFORE ATTEMPTING TO SEND A TEST BY SETTING $TEST!!!
//session_start();
//set_time_limit(0);
//ini_set('max_execution_time', 75);


// LOCKFILE
// check for lockfile - make sure we're not duplicating processes
$lockfile = "/tmp/mobmov_mailer_lockfile";
if (file_exists($lockfile)) {
		// email the admin about it
        email("info@mobmov.org","info@mobmov.org","MobMov Mailer","Lockfile found!","$lockfile exists, exiting");	
		die("Mailer locked! Remove lockfile from $lockfile");
} else {
	$fp = fopen($lockfile, 'a+');		//create empty file
	fclose($fp);
}


// basically goes until max reached. we send all mails by running every 2 minutes and keeping a running log of where we've completed stuff



//die("fake");
db_connect();
$sql = "SELECT * FROM mailings WHERE send_to != '' AND NOW() > datesend";
$result = mysql_query($sql);

$whole_count = 0;

if ($result && mysql_num_rows($result)>0){
	
    // EACH MAILER
	while($row = mysql_fetch_array($result)){ 
		$mailing_body = "";
	    $mailing_subject = "";
	    $mailing_send_to = "";
	    
	  foreach($row AS $key => $val){ 
	   	$key = "mailing_".strtolower($key);
	   	$$key = $val; 
	  }
	  
	  
	  $sql = "SELECT members.email,chapters.city FROM members LEFT JOIN chapters ON members.member_id = chapters.coord_id WHERE chapters.chapter_id = '$mailing_chapter_id'";
	  $result_inner = mysql_query($sql);
	  if (mysql_num_rows($result_inner)>0) {
	 	$from_email = mysql_result($result_inner,0,0); // renable to allow reply to chapter head (disabled for spam)
	  	$from_location = "mobmov - " . mysql_result($result_inner,0,1); 
	  }
	  mysql_free_result($result_inner);
	  if ($from_location='') {
	    $from_location = "mobmov";
	  }
	  if (!$from_email) { 
	  	$from_email = "info@mobmov.org";
	  }
	  
	  $fromname = $from_location;
	  
	  $count = 0;
	  
	  
	 // check if it's just a comma and delete it
	 if ($mailing_send_to == ',') {
		$sql_rem = "UPDATE mailings SET send_to = '' WHERE mailing_id = '".$mailing_mailing_id."'";
	    mysql_query($sql_rem);
		$mailing_send_to='';
	 }
	  
		$sending_to = split(",",stripslashes($mailing_send_to));
		
		// EACH EMAIL
		
		require_once ($_SERVER['DOCUMENT_ROOT']."/mobmov.org/common/class.phpmailer.php"); // important b/c email includes it already
		$mail = new PHPMailer();
		//$mail->isSMTP();
		//$mail->SMTPKeepAlive = true; 
		//$mail->Host = "smtp.google.com";
		//$mail->Port =
		
	    foreach ($sending_to as $email) {
	    	$sendnow = false;
	    	
	        if ($count < $max_sends) {
				$emailorig=$email;
	            $email = filterformat(EMAIL,$email);
	            // check if e-mail format is correct and is not a duplicate
	        	if ($email && !strpos($mailing_sent_to,$email)) {
	
	            	$thisbody = stripslashes($mailing_body);
	            	$remove_from_list = "\r\n\r\nMobmov loves you. If you don't love the mobmov back, simply click this link to be unsubscribed immediately:\r\nhttp://mobmov.org/?cha=".$mailing_chapter_id."&remv=".$email;
	
	                $thisbody = str_replace("%email%",$email,$thisbody);
	                $thisbody = str_replace("%rsvp%","http://mobmov.org/?rsvp=$mailing_showing_id&email=$email",$thisbody);
	                
	        		//$body    = stripslashes(nl2br($thisbody.$strbodyh));
	           		$body_send = strip_tags($thisbody.$remove_from_list);
	                $subject = stripslashes($mailing_subject);
	            	
	                //if ($test && $count < $test_sends) {    // todo: depriciated
	                	//$email="info@mobmov.org";
	                    //$count++;
	                    //email($email,$from,$fromname,"test -- ".$subject,$body);
	                    //$send_to = "";	// only sent us 10 test messages, then quit
	                //if (!$test) {
	                    
	                    $return .= "\nEMAIL: $email";
	                    
	                    //double-check to stop duplicates from sending (if two of this script are running!)
	                    //$sql_check = "SELECT sent_to FROM mailings WHERE mailing_id = '".$mailing_mailing_id."'";
						  //$result_check = mysql_query($sql_check);
						  //$real_sent_to_sofar = mysql_result($result_check,0,0);
						 // $real_sent_to_sofar = split(",",$real_sent_to_sofar);
						  //if (!in_array($email,$real_sent_to_sofar)) {
						  
						  
							$mail->AddAddress($email);
							//$mail->AddAddress('plusbryan@gmail.com');
							$mail->Subject = $subject;
							$mail->Body    = $body_send;
							$mail->From     = $from_email;
							$mail->FromName = $fromname;
							
							$mail->Send();
							$mail->ClearAddresses();
	
						  

                        	$fromname = "MobMov";
                            //email($email,$from_email,$fromname,$subject,$body_send);	
                            
                            $count++;
	                    	$whole_count++;
	                    	
						  //} else {
						  	//email('info@mobmov.org',$from,$fromname,$subject,'ERROR::this email was already sent!! (skipping)'.$body_send);
						  	
						  //}
	                    //email("info@mobmov.org",$from,$fromname,$subject,$body_send);
	
	                    //$sendnow = true
	                //} else {	// this is a test but enough tests sent already
	                  	//$sendnow = true;	// delete all the extra test e-mails, so this loop only fails (and doesn't remove an address) if the send fails
	                //}
	            	
	              
	                //if ($sendnow) {			// remove this email from the send list if send successful or test sending over
	                    $mailing_send_to = str_replace($email,"",$mailing_send_to);
	                    $mailing_send_to = str_replace(",,","",$mailing_send_to);
	                    $mailing_send_to = str_replace(" ","",$mailing_send_to);
	                    $mailing_sent_to .= ",".$email;
	                    if ($mailing_send_to == ",") { $mailing_send_to = ""; }
	                    
	                    $sql_rem = "UPDATE mailings SET datesent=NOW(), send_to = '".addslashes($mailing_send_to)."', sent_to = '".addslashes($mailing_sent_to)."' WHERE mailing_id = '".$mailing_mailing_id."'";
						
	                    $result_rem = mysql_query($sql_rem);
	            
	            	//}
	                   
	                
	           
	            // bad e-mail address in file, remove from mailing (maybe also from list??)
	    		} else {	
	            	if($emailorig) {
	            		$mailing_send_to = str_replace($emailorig,'',$mailing_send_to);
	                    $mailing_send_to = str_replace(',,',',',$mailing_send_to);
						$mailing_send_to = str_replace(',,',',',$mailing_send_to);
	                    $mailing_send_to = str_replace(' ','',$mailing_send_to);
	                    $sql_rem = "UPDATE mailings SET send_to = '".addslashes($mailing_send_to)."' WHERE mailing_id = '".$mailing_mailing_id."'";
	                    mysql_query($sql_rem);
	                }
	            } 
	        }         
	    }
		
		//$mail->SmtpClose(); 
		
	    
	    $return .= "\n\nmailing ".$mailing_mailing_id." end. sent to: ".$count;
        
		if ($whole_count > 0) {
	        $to = "info@mobmov.org";
	    	$fromname = "Mobmov.org";
	    	$from = "info@mobmov.org";
	    	$subject = "MAILER SENT: $subject";
	    	$body = "sent to: $whole_count emails:\r\n $body_send";
	        //email($to,$subject,$body);	
		}
        
	    // clear off mailing list (always assuming we're done!)
	    //$sql = "UPDATE mailings SET send_to='' WHERE mailing_id=".$mailing_mailing_id."";
		//$result = mysql_query($sql);
	}
	mysql_free_result($result);
}

unlink($lockfile);
print "done";

if ($whole_count > 0) {
    $return .= "\n\nEND sent to count: $whole_count";
    print $return;
}
