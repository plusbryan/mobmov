<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");
$verbose[] = "<b>mailings.php</b>";

if(!$global_islogged){
	header("Location: index.php");
	exit;
}
db_connect();
$archive = $_GET['archive'];
$type = $_GET['type'];
$new = $_GET['new'];
$setup = $_GET['setup'];
$mailing_id = $_GET['id'];

if ($global_superuser) {
	$chaptersql .= " OR chapter_id='0'";
   }
if ($mailing_id==0) {
	if ($archive) {
		$sql = "SELECT *,UNIX_TIMESTAMP(s.datesent) AS datesent FROM mailings AS s WHERE (chapter_id =" . $chaptersql . ") AND TRIM(send_to) = ''";
    } else {
   	 	
    	$sql = "SELECT *,UNIX_TIMESTAMP(s.datesend) AS datesend FROM mailings AS s WHERE (chapter_id =" . $chaptersql . ") AND TRIM(send_to) <> ''";
    	$pending = true;
    }
    $show_all = true;
   
} else {
	$sql = "SELECT *,UNIX_TIMESTAMP(s.datesend) AS datesend FROM mailings AS s WHERE s.mailing_id ='$mailing_id'";
}

$result = mysql_query($sql);
$verbose[] = $sql;

//$page_refresh = 100;
?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
            
            <tr>
				<? if ($setup) { ?>
				<td style="padding-bottom:10px;">
                    <span class="page_head">New Mailing</span>
                </td>
                <? } else { ?>
                <td valign="top">
                <span class="page_head">Mailings</span><br>
                <span class="page_subhead">Use this section to send out mail to the subscribers of your chapter.</span><hr size="1" width="100%" noshade color="#dddddd"><br>
                </td>
                <? } ?>
            </tr>
                <? if (!$new) { ?>
              <tr>
                <td>

<? 


if (mysql_num_rows($result)>0) {?>

                	<? if ($pending) {?><b><font size=4>Pending</font></b>&nbsp;&nbsp;<a href="mailings.php?archive=y">Archive</a><? } else { ?><b><font size=4>Archive</font></b>&nbsp;&nbsp;<a href="mailings.php">Pending</a><? } ?><br>
                	<br><table border="0" cellpadding="4" cellspacing="0" width="100%">
					<?
					if ($show_all) {
                    
                    
                     	if ($pending) 
                     	{
                     	
                     	?>
                    	 <tr>
                      		<td class="line-top">Subject</td>
                            <td class="line-top">Chapter</td>
                      		<td class="line-top">Date Send</td>
                            <td class="line-top">Remain</td>
                      		<td>&nbsp;</td>
					 	 </tr>
                         
                       <? 
                       } else { 
                       ?>
                         <tr>
                      		<td class="line-top">Subject</td>
                            <td class="line-top">Chapter</td>
                      		<td class="line-top">Date Sent</td>
                      		<td class="line-top">Remain</td>
                      		<td>&nbsp;</td>
					 	 </tr>
                       <? 
                       }
                     
					
                    
					}
					
					
					
					while($row = mysql_fetch_array($result)){ 
						foreach($row AS $key => $val){ 
							$key = strtolower($key);
							$$key = $val; 
						}
                        
                        if ($show_all) {
                        	if ($archive || strlen(trim($send_to))>0) {
					?>     
                          	  <tr>
                                <td class="line0"><?=$subject?></td>
                                <td class="line0"><? if (!$chapter_id) {?>All Chapters<? } else {?><?=get_chapter_name($chapter_id)?><? }?></td>
                                 <? if ($pending) {?>
                                	<td class="line0"><? if (sdatetime($datesend) > time()) { print sdatetime($datesend); } else { print "Sending..."; }?></td>
                                	<td class="line0"><?=substr_count($send_to,",")?></td>
                                <? } else { ?>
                                	<td class="line0"><?=sdatetime($datesent)?></td>
                                 <? } ?>
                                <td class="line0">
                                	<? if ($pending) {?>
                               		<input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='mailings.php?id=<?=$mailing_id?>';">&nbsp;&nbsp;
                                	<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=mailings&col=mailing_id&id=<?=$mailing_id?>';">&nbsp;&nbsp;
                                	<? } ?>
                                </td>
                              </tr>
                              
					  <?
                      		}
                      	} else {
                        ?>	
                        
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="mailing_id">
                                    <input type="hidden" name="mailing_id" value="<?=$mailing_id?>">
                                    <input type="hidden" name="table" value="mailings">
                                    <input type="hidden" name="return" value="mailings.php">
                                  
                                  <tr>
                                    <td align="right"><b>Subject</b></td>
                                    <td><input name="subject" type="text" class="form_text" size="45" value="<?=$subject?>"></td>
                                  <tr>
                                   <tr>
                                    <td align="right" valign="top"><b>Body</b></td>
                                    <td><textarea name="body" cols="85" rows="25" class="form_text"><?=$body?></textarea></td>
                                  </tr>
                                    <td align="right"><b>Date Send</b></td>
                                    <td>
									
											<!-- select date and time -->
											<table border=0 cellpadding=1><tr><td><script>DateInput('datesend_date', true, 'YYYY-MM-DD', '<?=date("Y-m-d",$datesend);?>')</script></td><td> at
											<input name="datesend_time!" type="text" class="form_text" size="9" value="<?=date("g:i A",$datesend);?>"></td></tr></table>
											<!-- end -->
									
									</td>
                                  </tr>
                                
                                 
                                   
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Update">&nbsp;<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=mailings&col=mailing_id&id=<?=$mailing_id?>';">&nbsp;<a href="mailings.php">Back</a></td>
                                  </tr> 
                     		 	</form>
					  <?
                        }
					  }
					  
					  
					  ?>
					  
                	</table>
                	<? }
					  mysql_free_result($result);?>
                </td>
              </tr>
              
              <tr>
                <td><br><b>Send New Mail:<br/></b>&nbsp;
                	<table style="font-size:14pt;"><tr><td style="padding:12px;border:1px solid #333;margin:10px;"><a href="mailings.php?type=general&new=y">General</a></td>
                		<td style="padding:12px;border:1px solid #333;margin:10px;"><a href="movies.php?setup=y">Showing</a></td><td style="padding:12px;border:1px solid #333;margin:10px;"><a href="mailings.php?new=y&type=poll">Poll</a> </td></tr></table>
              </tr>
              <? } else { ?>
              

         
              <tr>
                <td>
              
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
				  
                      <? if ($type == "poll") {?>

<!-- POLL MAILING -->
                    
                      <form name="form2" method="post" action="mailings_preview.php" enctype="multipart/form-data">
                                   <input type="hidden" name="return" value="mailings.php"> 
									<input type="hidden" name="poll" value="y">
                      
                        <tr>
                      <td>
                      
                      
                      
                      	<span class="font-blue">&raquo; Add New Poll Mailing </span>&nbsp;&nbsp;<a href="mailings.php">Back</a>
                      
                      
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
                      
                      <table border="0" cellspacing="1" cellpadding="4">
                          		
								<tr>
                                    <td><b>To Chapter:</b></td>
                                    <td><select name="chapter_id">
                                        
                                        <?
                                        $sql2 = "SELECT chapter_id,title,city,state FROM chapters WHERE chapter_id =" . $chaptersql;
                                        $result2 = mysql_query($sql2);
                                        while($row2 = mysql_fetch_array($result2)){
                                        	foreach($row2 AS $key2 => $val2){  
                    							$key2 = "it_".strtolower($key2);
                    							$$key2 = $val2; 
                    						}
    										?>
    										<option value="<?=$it_chapter_id?>" ><?=$it_city?>, <?=$it_state?></option>
                                <? 
										}
										mysql_free_result($result2);										
									?>
    
    									</select>
                                    </td>
                                  </tr>
                             
							 
                                  
                                  <tr>
                                    <td align="right" valign="top"><b>Subject</b></td>
                                    <td>[mobmov]<input name="subject" type="text" class="form_text" size="45" value="Call for Votes!"> <br><span style="color:#666666">Default: [mobmov] Call for votes</span></td>
                                  </tr>
                                 
                                 <?
                               
									$template = file_get_contents("templates/polls/default.txt");
									$template = showing_template_fill($template,$showing_id);
									
								
								?>
                                  
                                  <tr>
                                    <td align="right" valign="top"><b>Body:</b></td>
                                    <td><textarea name="body" cols="85" rows="25" class="form_text"><?=$template?></textarea><br/><i>Note: %????% links will be replaced at send time with the appropriate link.</td>
                                  </tr>
                                 
                                  
                                   <tr>
                                    <td align="right" valign="top"><b>Poll Close Limit</b></td>
                                    <td style="color:#666666"><input name="endcount" type="text" class="form_text" size="15" value=""><br>Optional; To close the poll after a specific number of votes.</td>
                                  </tr>
                                
                                 
                                 
                                  <tr>
                                    <td></td>
                                    <td><b>Now for the poll options:</b><br>You do not have to offer all 5 choices. Feel free to add links and simple HTML:</td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"></td>
                                    <td><b>Choice 1</b> <input type="text" name="choice_1" size="35" value="<?=$choice_1?>"> e.g. Pulp Fiction</td>
                                  </tr>
                                 <tr>
                                    <td align="right" valign="top"></td>
                                    <td><b>Choice 2</b> <input type="text" name="choice_2" size="35" value="<?=$choice_2?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"></td>
                                    <td><b>Choice 3</b> <input type="text" name="choice_3" size="35" value="<?=$choice_3?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"></td>
                                    <td><b>Choice 4</b> <input type="text" name="choice_4" size="35" value="<?=$choice_4?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"></td>
                                    <td><b>Choice 5</b> <input type="text" name="choice_5" size="35" value="<?=$choice_5?>"></td>
                                  </tr>
                         
						 
									<tr>
                                    <td align="right" valign="top"><b>When to send?</b></td>
                                    <td>
									
									
											<!-- select date and time -->
												<table border=0 cellpadding=1><tr><td><script>DateInput('datesend_date', true, 'DD-MM-YYYY')</script></td><td> at
												<input name="datesend_time" type="text" class="form_text" size="9" value="<?=date("g:i A");?>"></td></tr></table>
											<!-- end -->
									
									</td>
                                  </tr>
								  
								  
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td>
                                    <input name="Submit" type="submit" class="form_button" value="Preview">&nbsp;</td>
                                  </tr> 
                         
                      </table></td>
                    </tr>
                    
                    <? } else { ?>
                    
<!-- GENERAL MAILING -->

                    <form name="form2" method="post" action="mailings_preview.php" enctype="multipart/form-data">
       				  <input type="hidden" name="table" value="mailings">
                      <input type="hidden" name="return" value="mailings.php">
                      <? if (!$setup) { ?>
                    <tr>
                      <td>

                      	<span class="font-blue">&raquo; New Mailing </span><br>
                      
                      
                      </td>
                    </tr>
                        <? } ?>
                <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table border="0" cellspacing="1" cellpadding="4">
                          		
                          		<? 
								$showing_id = $_REQUEST['showing_id'];
								if ($showing_id) {
										?>
										<input type="hidden" name="showing_id" value="<?=$showing_id?>">
							 <? } else { ?>
							 
									<tr>
										<td><b>To Chapter:</b><br/><select name="chapter_id">
											<? 
											if ($global_superuser){ ?>
											
												<option value="drivers">Chapter Drivers</option>
												
												<option value="all">Everyone</option>
												<option value=""> </option><option value=""></option>
											<? 
											} 
											
																					
											// your chapters
											$sql2 = "SELECT chapter_id,city,UPPER(state) as state,UPPER(country) as country FROM chapters WHERE (chapter_id =" . $chaptersql . ") AND approved='y' ORDER BY country,state,city";
											$result2 = mysql_query($sql2);
											while($row2 = mysql_fetch_array($result2)){
												foreach($row2 AS $key2 => $val2){  
													$key2 = "it_".strtolower($key2);
													$$key2 = $val2; 
												}
												
												?><option value="<?=$it_chapter_id?>" ><?=$it_city?> <?=$it_state?>, <?=$it_country?></option><? 		
											}
											mysql_free_result($result2);	
											
											?>
											<option value=""></option><option value=""></option>
											<?
											
											// your upcoming shows
											$sql2 = "SELECT showing_id,showtime,rsvps FROM showings AS s WHERE showtime >= DATE_SUB(NOW(),INTERVAL 15 DAY) AND (chapter_id =" . $chaptersql.")";
											$result2 = mysql_query($sql2);
											while($row2 = mysql_fetch_array($result2)){
												foreach($row2 AS $key2 => $val2){  
													$key2 = "it_".strtolower($key2);
													$$key2 = $val2; 
												}
												
												?><option value="showing_<?=$it_showing_id?>"><?=dbtousdate($it_showtime)?> show RSVPs (<?=$it_rsvps?>)</option><?
											}
											mysql_free_result($result2);
											
	?>
		
											</select>
										</td>
									  </tr>
                                  
                                 <? }
                                 
								if ($showing_id > 0) {
									$template = file_get_contents("templates/showings/default.txt");
									$template = showing_template_fill($template,$showing_id);
									$subject = get_showing_subject($showing_id);
								}
		?>
                                 
                                  <tr>
                                    <td><b>Subject:</b><br/>
                                    <input name="subject" type="text" class="form_text" size="65" value="<?=$subject?>">
                                    </td>
                              
                                  </tr>
   
                                  <tr>
                                    <td><b>Body:</b><br/><textarea name="body" cols="85" rows="25" class="form_text"><?=$template?></textarea><br/><? if ($showing_id>0) {?><i>Note: %rsvp% will be replaced at send time with the appropriate link.<? } ?></td>
                                 
                                  </tr>
                                  <tr>
                                    <td><b>Send Date:</b><br/>
                                    <!-- select date and time -->
										<table border=0 cellpadding=1><tr><td><script>DateInput('datesend_date', true, 'YYYY-MM-DD')</script></td><td> at
										<input name="datesend_time" type="text" class="form_text" size="9" value="<?=date("g:i A");?>"></td></tr></table>
										<!-- end -->
									 (defaults to ASAP)
									 </td>
                                  
                                  </tr>
                                   
                         
                                  <tr>
                                    <td><input name="Submit" type="submit" class="form_button" value="Preview">&nbsp;</td>
                                  </tr> 
                         
                      </table></td>
                    </tr>
                    
                    	
                        
                        
                        
                        <? }
mysql_free_result($result);						?>
                    
					</form>
                  </table>
                </td>
              </tr>
              <? } ?>
            </table>
        
        
        <? require_once("inc_bot.php"); ?>