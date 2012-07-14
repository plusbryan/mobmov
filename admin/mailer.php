<?
require("inc_functions.php");


$listtype = request('listtype',POST);
$subject = request('subject',POST);
$body = request('body',POST);
$fromemail = request('fromemail',POST);
$fromname = request('fromname',POST);
$scheduled= request('scheduled',POST);
$scheduled_time= request('scheduled_time',POST);
$scheduled = strtotime("$scheduled $scheduled_time");
$subcount=0;

if ($listtype && $body && $fromemail) {

	// save message in message table
	$sql = "INSERT INTO mailinglist_messages SET subject='$subject',body='$body',fromemail='$fromemail',fromname='$fromname',dtCreated=NOW(),listtype='$listtype',dtScheduled=FROM_UNIXTIME($scheduled)";
	$message_id = mysql_query_safe($sql);
		
	$status_message = "Message '$subject' <a href='mailer_messages.php'>queued</a> for sending...";
	
} else if (request('action',POST)=="send") {
	$status_message = "You did not fill out the required fields";

}




?>
<? require("inc_top.php"); ?>

	<table border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td valign="top">
            <span class="page_head">Mailer</span>&nbsp;&nbsp;&nbsp;<a href="mailer_messages"><b><u>Messages</u></b></a>
			<br/><br/>
                            	
                <table border="0" cellpadding="4" cellspacing="0">
                
                
	
       				<form action="mailer.php" method="post">
                      <input type="hidden" name="action" value="send">
                      
                      <tr>
                        <td align="right" valign="top"><b>From Email</b></td>
                        <td><input name="fromemail" type="text" class="form_text" size="45" value="<?=$fromemail?>"><br/></td>
                      </tr>
					  <tr>
                        <td align="right" valign="top"><b>From Name</b></td>
                        <td><input name="fromname" type="text" class="form_text" size="45" value="<?=$fromname?>"><br/></td>
                      </tr>
					  
					  <tr>
                        <td align="right" valign="top"><b>List</b></td>
                        <td>
						<select name="listtype">
						
						<? 
						$sql = "SELECT COUNT(*) as counttype,listtype FROM mailinglist_subscribers GROUP BY listtype";
						$result = mysql_query_safe($sql);
						
						$total =0;
						while ($row = mysql_fetch_assoc($result)) {
							$total += $row['counttype'];
							$count = number_format($row['counttype']);
							$list = $row['listtype'];
							print "<option value='$list'>$list ($count)</option>";
						}
						?>
						</select> <?=number_format($total)?> subscribers in all lists
						
						
						
						</td>
                      </tr>
					  
					  <tr>
                        <td align="right" valign="top"><b>Subject</b></td>
                        <td><input name="subject" type="text" class="form_text" size="45" value="<?=$subject?>"><br/></td>
                      </tr>
					  
					  <tr>
                        <td align="right" valign="top"><b>Body</b></td>
                        <td><textarea name="body" class="form_text" rows="10" cols="60"><?=$body?></textarea><br/>Mail merge variables: %email%</td>
                      </tr>
					  
                      
					  
					  <tr>
                                    <td align="right" valign="top"><b>Send Date</b></td>
                                    <td>
									
											<!-- select date and time -->
											<table border=0 cellpadding=1><tr><td><script>DateInput('scheduled', false, 'YYYY-MM-DD', '<?=date("Y-m-d");?>')</script></td><td> at
											<input name="scheduled_time" type="text" class="form_text" size="7" value="<?=date("g:i A");?>"> PST</td></tr></table>
											<!-- end -->
									
									
                                  </tr>
                     
                      
                  
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td><input name="Send Mail" type="submit" class="form_button" value="Next"> You will confirm on next page.</td>
                      </tr> 
         		 	</form>
	  
	  
            	</table>
            </td>
          </tr>
          
        </table>
        
<? require("inc_bot.php"); ?>