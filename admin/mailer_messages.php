<?
require("inc_functions.php");

$table = "mail_messages";
$id = request('id',GET);
if (!$id) {
	$sql = "SELECT * FROM $table ORDER BY listtype";
    $show_all = true;
    $result = mysql_query_safe($sql);
	$edit = false;
} else {
	if ($id == "NEW") {
    	$new = true;
    } else {
		$sql = "SELECT * FROM $table WHERE id ='$id'";
		$result = mysql_query_safe($sql);
		$new = false;
    }
    $edit = true;
	$show_all = false;
}


?>
<? require("inc_top.php"); ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">

  <tr>
	<td valign="top">
	
	
	<span class="page_head">Mailer: Messages</span>&nbsp;&nbsp;&nbsp;<a href="mailer"><b><u>Back</u></b></a>&nbsp;&nbsp;
	<br/><br/>
		
		
		<table border="0" cellpadding="4" cellspacing="0">
		<?
		if ($show_all) {
		?>
			<tr>
				<td class="line-top line-left"><b>Subject</b></td>
				<td class="line-top" align="center"><b>Type</b></td>
				<td class="line-top" align="center"><b>Body</b></td>
				<td class="line-top" align="center"><b>From</b></td>
				<td class="line-top" align="center"><b>Queued</b></td>
				<td class="line-top" align="center"><b>Unsubscribes</b></td>	
				<td>&nbsp;</td>
			 </tr>
		   
		<?
		
		}
		
			if ($show_all) {
				while($row = mysql_fetch_array($result)) { 
					extract($row, EXTR_PREFIX_ALL, "message");
					
					// count queue
					$sql = "SELECT count(*) FROM mail_queue WHERE message_id='$message_id'";
					$inner = mysql_query_safe($sql);
					$message_count_queue = @mysql_result($inner,0,0);
					@mysql_free_result($inner);
					//$message_subject =$message_subject ? $message_subject : "message #$message_id";
		?>     
				  <tr>
					<td class="line line-left"><?=defaultto($message_subject,"(no subject)")?></td>
					<td class="line" align="center"><?=$message_listtype?>&nbsp;</td>
					<td class="line"><?=truncate($message_body,45)?></td>
					<td class="line"><?=truncate("$message_fromname <$message_fromemail>",30)?>&nbsp;</td>
					
					<td class="line" align="center"><?=$message_count_queue?>&nbsp;</td>
					<td class="line" align="center"><?=$message_count_unsubscribes?>&nbsp;</td>
					<td>
					<nobr><input name="Button" type="button" class="form_button" value="delete" onClick="ask('Are you sure you want to delete this?','delete.php?table=<?=$table?>&col=id&id=<?=$message_id?>');">
					</nobr>
					</td>
				  </tr>
		  <?	}
		  
			}
			
			?>	
		 
		  
		</table>
	</td>
  </tr>
  <tr>
  <td><br/><br/>
  <a href="mailer.php"><b><u>Make New</u></b></a>
  </td>
  </tr>
  
</table>
        
<? require("inc_bot.php"); ?>