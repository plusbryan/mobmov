<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");

if(!$global_islogged){
	header("Location: index.php");
	exit;
}

db_connect();

?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0">
				
              <tr>
                <td valign="top"><?
                
               
?>
<form name="form2" method="post" action="save.php">
       				  
                      <?
	$return = "mailings.php";

    
    	$body = $_REQUEST['body'];
    	
	    while(list($key, $val) = each($_REQUEST))
	    {
	    		// replace variables in body and set to variables here
	       		$keyfob = strtolower($key);
				$key = "it_".strtolower($key);
	        
				$$key = $val;
				
				 // get chapter name
      			if ($key == "it_showing_id") {
			    	$sql = mysql_query("SELECT chapter_id FROM showings WHERE showing_id = '$val'");
			   		$it_chapter_id = mysql_result($sql,0,0);
				}
				
				 if ($val) {
           	 		$body = str_replace("%$keyfob%","$val",$body);
            	}
	                                
	    }
     
        $allrep = array();

		// GET USER TABLE OF SENDS
		if ($it_chapter_id == "all") {
			$sql ="SELECT email FROM mailinglist";
			$mailing_type = "entire mailing list";
			$it_chapter_id='';
		} else if ($it_chapter_id == "drivers") {
			$sql ="SELECT m.email FROM chapters AS c CROSS JOIN members AS m ON (c.coord_id = m.member_id) WHERE m.email <> ''";
			$mailing_type = "all drivers";
			$it_chapter_id="";
		} else if (substr($it_chapter_id,0,8) == "showing_") {	//send only to showing rsvps
			$sql ="SELECT rsvp_list FROM showings WHERE showing_id = '".substr($it_chapter_id,8)."'";
			$mailing_type = "all rsvps for this showing";
			$it_chapter_id="";
			$it_showing_id=str_replace('showing_','',$it_chapter_id);
		} else {
			$sql ="SELECT email FROM mailinglist AS m CROSS JOIN mailinglist_chapters AS c USING (member_id) WHERE c.chapter_id = '$it_chapter_id'";
			$mailing_type = get_chapter_name($it_chapter_id);
			$add_chapter_heads=true;
		}
	
		$result = mysql_query($sql);
		$allusers="";
		$count_subscribers=0;
		//print $sql;

		while($row = mysql_fetch_array($result)){ 
			$allusers .= ",".$row['email'];
			$count_subscribers++;
		}
		mysql_free_result($result);

                        
		$allusers = "info@mobmov.org,".$allusers;
	   
	   
	   // set up poll
	   if ($it_choice_1<>'') {
			$sql = "INSERT INTO showings_votes SET choice_1='$it_choice_1',choice_2='$it_choice_2',choice_3='$it_choice_3',choice_4='$it_choice_4',choice_5='$it_choice_5',chapter_id='$it_chapter_id',subject='$it_subject',endcount='$it_endcount',canvote='$allusers'";
			$result = mysql_query_safe($sql);
			$it_showing_poll_id = mysql_insert_id();
			
			if (!$it_subject) {
				$it_subject = "Call for votes";
			}
			$body = str_replace("%subject%",$it_subject,$body);
			
			if ($it_choice_1<>"") { 
				$body = str_replace("%choice_1%",$it_choice_1,$body);
				$body = str_replace("%choice_1_vote%","\nVote: http://mobmov.org/vote?poll=$it_showing_poll_id&vote=1&u=%email% \n\n",$body);
			} else {
				$body = str_replace("%choice_1%","",$body);
				$body = str_replace("%choice_1_vote%","",$body);
			}
			if ($it_choice_2<>"") { 
				$body = str_replace("%choice_2%",$it_choice_2,$body);
				$body = str_replace("%choice_2_vote%","\nVote: http://mobmov.org/vote?poll=$it_showing_poll_id&vote=2&u=%email% \n\n",$body);
			} else {
				$body = str_replace("%choice_2%","",$body);
				$body = str_replace("%choice_2_vote%","",$body);
			}
			if ($it_choice_3<>"") { 
				$body = str_replace("%choice_3%",$it_choice_3,$body);
				$body = str_replace("%choice_3_vote%","\nVote: http://mobmov.org/vote?poll=$it_showing_poll_id&vote=3&u=%email% \n\n",$body);
			} else {
				$body = str_replace("%choice_3%","",$body);
				$body = str_replace("%choice_3_vote%","",$body);
			}
			if ($it_choice_4<>"") { 
				$body = str_replace("%choice_4%",$it_choice_4,$body);
				$body = str_replace("%choice_4_vote%","\nVote: http://mobmov.org/vote?poll=$it_showing_poll_id&vote=4&u=%email% \n\n",$body);
			} else {
				$body = str_replace("%choice_4%","",$body);
				$body = str_replace("%choice_4_vote%","",$body);
			}
			if ($it_choice_5<>"") { 
				$body = str_replace("%choice_5%",$it_choice_5,$body);
				$body = str_replace("%choice_5_vote%","\nVote: http://mobmov.org/vote?poll=$it_showing_poll_id&vote=5&u=%email% \n\n",$body);
			} else {
				$body = str_replace("%choice_5%","",$body);
				$body = str_replace("%choice_5_vote%","",$body);
			}
										
			$it_subject = "$it_subject";
			
		}
                        
                        ?>
<input type="hidden" name="table" value="mailings">
<input type="hidden" name="return" value="<?=$it_return ?>">
<input type="hidden" name="chapter_id" value="<?=$it_chapter_id ?>">
<input type="hidden" name="showing_id" value="<?=$it_showing_id ?>">

<input type="hidden" name="subject" value="<?=stripslashes($it_subject) ?>">
<input type="hidden" name="datesend_time!" value="<?=$it_datesend_time?>">
<input type="hidden" name="datesend_date" value="<?=$it_datesend_date?>">
<input type="hidden" name="body" value="<?=stripslashes(htmlentities($body))?>">
<input type="hidden" name="send_to" value="<?=$allusers?>">

<font size=4>To <?=$count_subscribers?> subscribers of <?=$mailing_type?>:<br></font>
<table border="0" cellspacing="3" cellpadding="6" width="500">
		<tr>
			<td bgcolor="#dddddd"><b><?=stripslashes($it_subject)?></b></td>
		  </tr>
		   <?
		   $body = str_replace("%rsvp%","<b>(RSVP link)</b>",$body);
		   ?>
  <tr>
			<td bgcolor="#dddddd"><?=stripslashes(nl2br($body))?></td>
		  </tr>
		  
		
                         
                      </table>
                      <br><input name="Submit" type="submit" class="form_button" value="Send"> <a href="mailings.php">Cancel</a>
                     <br><br>on or after <?=$it_datesend_date?> @ <?=$it_datesend_time?>&nbsp;

                    
					</form>
                </td>
              </tr>
        
            </table>
        
        
        <? require_once("inc_bot.php"); ?>
