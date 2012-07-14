<? require("common/top.php"); ?>

<IMG src="pictures/052505-a.jpg" width="200" height="142" align="right" border=2 hspace="8">
<span class="header-page">signup</span><br><br>

<?
// action comes from func_mailinglist.php
if ($action == "more") {
    ?>
    <b>Thanks & welcome!</b><br>

	<?

	
} else if ($action == "addtolist" || $action == "confirm") {
	
	$email = $_GET['email'];
	if ($email) { 
		$SQL ="SELECT member_id FROM mailinglist WHERE email='$email'";
		$result = mysql_query($SQL);
		$member_id = mysql_result($result,0,0);
	}
	$mem = $_GET['mem'];
	if ($mem) {
		$SQL ="UPDATE mailinglist SET confirmed=1,dtConfirmed=NOW() WHERE member_id='$mem'";
		$result = mysql_query($SQL);
		$success_msg="<b>Your email address has been confirmed, thank you and welcome!</b><br><br>";
	}
?>

<?=$success_msg?>

	 <span class="header">any more chapters?</span><br>
    would you like to subscribe to any other chapters to be notified when they also hold events?

	<FORM id="MailingList" action="signup.php" method="get" name="MailingList">
    <INPUT type="hidden" name="action" value="more">	
    <INPUT type="hidden" name="member_id" value="<?=$member_id?>">			

	                                	<select name="chapters[]" multiple size=12>
	                                
	                            <?
	                           
	                    $preselect_chapter = $_GET['preselect_chapter'];
	                        
	                    db_connect();
	                    // get current subscriptions
	                    $SQL ="SELECT chapter_id FROM mailinglist_chapters WHERE member_id='$member_id'";
	                    $result = mysql_query($SQL);
	                    $chapter_ids = array();
	                    //$chapter_ids="";
	                    while($row = mysql_fetch_assoc($result)){ 
	                    	$chapter_ids[] = $row['chapter_id'];
	                    }
	                    
                         $last_country ="";
	                   	// get chapters list
	                    $SQL ="SELECT chapter_id,city,state,country,new,IF(DATE_ADD(created,INTERVAL 60 DAY) >= NOW(),1,0) as new_created FROM chapters WHERE accepting = 'y' AND approved ='y' ORDER BY country DESC,state,city";
	                    $result = mysql_query($SQL);
	                    while($row = mysql_fetch_array($result)){ 	
	                    	$chapter_id = $row['chapter_id'];
	                        $new_created = $row['new_created'];
							$location = $row['city'];
	                        $country = $row['country'];
                            
                             if ($country != $last_country) {
                            	$last_country = $country;
                                print "<option value=''>".strtoupper($country)."</option>";
                            }
                            
	                        if (!$country) {
	                        	$country = "United States";
	                        }
	                        if ($row['state']) {
	                        	$location .= ", " . $row['state'];
	                        } else {
	                        	//$location .= ", " . $row['country'];
	                        }
	                        ?>
								<option value="<?=$chapter_id?>" <? if (in_array($chapter_id,$chapter_ids)) {?>selected<? } ?>>&nbsp;&nbsp;&nbsp;<?=$location?> <? if ($new_created) {?><? } ?></option>
	                            
	                            
	                            
	                  <? } ?>
	                            </select>
        <br/>To just get news &amp; announcements, deselect all chapters from this list.<br>
        <INPUT type="submit" name="join" value="Sign up">
	


    </FORM>
<?
} else if ($action == "setprefs") {
?>
	 <span class="header">tell others</span><br>
    The mobmov is only as strong as our community. Please do your part by sharing us with your friends! <br><br>

	<FORM id="MailingList" action="signup.php" method="get" name="MailingList">
    <INPUT type="hidden" name="action" value="others">				
    	<TABLE border="0" cellspacing="0" cellpadding="2" width="100%">
    		<TR>
    			<TD align="right" width="100" valign="top"><B>Friend's e-mails:</B></TD>
    			<TD><INPUT type="text" name="others[]" size="25" value=""><br><INPUT type="text" name="others[]" size="25" value=""><br><INPUT type="text" name="others[]" size="25" value=""><br><INPUT type="text" name="others[]" size="25" value=""><br><INPUT type="text" name="others[]" size="25" value=""><br><INPUT type="text" name="others[]" size="25" value=""><br></TD>
    		</TR>
    		<TR>
    			<TD>&nbsp;</TD>
    			<TD align="left"><INPUT type="submit" name="join" value="Invite them!"></TD>
    		</TR>
    	</TABLE>
    </FORM>
<?
} else {
	?>

<FORM id="MailingList" action="/signup.php?step=2" method="get" name="ML">
    <INPUT type="hidden" name="action" value="addtolist">


    <TABLE border="0" cellspacing="0" cellpadding="3">
        <tr><td rowspan=7 valign="top"><b><FONT size="3">join now</FONT></B>&nbsp;<FONT >and we'll e-mail you<br> about upcoming showings:</font><br/><br/><font class="small">we promise to never spam or <br/>otherwise misuse your information.</font></td></tr>

        <TR>
            <TD align="right" width="50"><B>email:</B></TD>
            <TD><INPUT type="text" name="email" size="25" value="<? print $email; ?>" TABINDEX=1></TD>

        </TR>
        <TR>
            <TD align="right"><B>zipcode:</B></TD>
            <TD><INPUT type="text" name="zip" size="25" value="<? print $zip; ?>" TABINDEX=2></TD>
        </TR>

        <TR>
            <TD align="right"><B>location:</B></TD>
            <TD>
                <select name="chapter" TABINDEX=3>
                    <option value="">news &amp; info</option>
                    <?

                    $preselect_chapter = $_GET['preselect_chapter'];

                    db_connect();
                    $SQL ="SELECT chapter_id,city,state,country,new FROM chapters WHERE accepting='y' AND approved ='y' ORDER BY country DESC,state,city";
                    $result = mysql_query($SQL);
                    $last_country ="";
                    $last_state ="";
                    while($row = mysql_fetch_array($result)){
                        $new = $row['new'];
                        $location = $row['city'];
                        $country = $row['country'];
                        if ($country != $last_country) {
                            $last_country = $country;
                            print "<option value=''>".strtoupper($country)."</option>";
                        }
                        if (!$country) {
                            $country = "United States";
                        }
                        if ($row['state']) {
                            $location .= ", " . $row['state'];
                        } else {
                            //$location .= ", " . $row['country'];
                        }
                        $chapter_id = $row['chapter_id'];
                        ?>
                        <option value="<?=$chapter_id?>" <? if ($preselect_chapter == $chapter_id) {?>selected<? } ?>>&nbsp;&nbsp;&nbsp;<?=$location?> <? if ($new == 'y') {?><? } ?></option>



                        <? } ?>
                </select></td></tr><tr><td></td><td>
        <INPUT type="submit" name="join" value="join the mob" TABINDEX=4></td></tr>


    </TABLE>


</FORM>
   
     
<?
}
?>


<? require_once("common/bot.php"); ?>
