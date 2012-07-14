<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");
$verbose[] = "<b>showings.php</b>";

if(!$global_islogged){
	header("Location: index.php");
	exit;
}

db_connect();

$showing_id = $_GET['id'];
$setup = $_REQUEST['setup'];
$setup_loc = $_REQUEST['setup_loc'];
$setup_mov = $_REQUEST['setup_mov'];

if ($setup && !$setup_mov) { header('Location: movies.php?setup=y&error=nomovie'); }

if (!$setup) {
	$sql = "SELECT *,UNIX_TIMESTAMP(s.showtime) AS showtime,chapter_id FROM showings AS s WHERE ";
	if (!$showing_id) {
		$sql .= "s.chapter_id = " . $chaptersql. "";
		$show_all = true;
	} else {
		$sql .= "s.showing_id ='$showing_id'";
	}
	$sql .= " ORDER BY s.showtime DESC";
	$result = mysql_query($sql);
}

$verbose[] = "$sql";

?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0">

            
              <tr>
                <td valign="top">
                    <?
                    if (!$setup) {
?>
                <span class="page_head">Shows</span><br>
                <span class="page_subhead">Set up and manage shows for your chapter here.</span><br><hr size="1" width="100%" noshade color="#dddddd"><br>
                        <?
                    }
?>
                	<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<?
					if ($show_all && !$setup && $result) {
                    ?>
                    	<tr>
                      		<td class="line-top"><b>Movie</b></td>
                            <td class="line-top"><b>Location</b></td>
                      		<td class="line-top"><b>Time</b></td>
                            <td class="line-top"><b>RSVPs</b></td>
                      		<td>&nbsp;</td>
					 	 </tr>
                        
					<?
                    
					}
					
					if ($result) {
					while($row = mysql_fetch_array($result)) {
                        
                        if ($show_all) {
					?>     
                          	  <tr>
                                <td class="line0"><?=get_movie_name($row['feature_movie_id'])?></td>
                                <td class="line0"><?=get_location_name($row['location_id'])?></td>
                                <td class="line0"><?=sdatetime($row['showtime'])?></td>
                                <td class="line0"><?=$row['rsvps'] ?></td>
                                <td>
                                	<nobr><input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='showings.php?id=<?=$row['showing_id']?>';">
                                	<input name="Button" type="button" class="form_button_red" value="Delete" onClick="JavaScript:location='delete.php?table=showings&col=showing_id&id=<?=$row['showing_id']?>';"><br>
                                	<input name="Button" type="button" class="form_button" value="Announce" onClick="JavaScript:location='mailings.php?new=y&type=showing&showing_id=<?=$row['showing_id']?>';">
                                	</nobr>
                                </td>
                              </tr>
<?
                      	} else {
                        	$edit = true;
                        	?>
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="showing_id">
                                    <input type="hidden" name="showing_id" value="<?=$row['showing_id']?>">
                                    <input type="hidden" name="table" value="showings">
                                    <input type="hidden" name="return" value="showings.php">

                                 
                                  <tr>
                                    <td align="right"><b>Dated</b></td>
                                    <td>
									
									
											<!-- select date and time -->
											<table border=0 cellpadding=1><tr><td><script>DateInput('showtime_date', true, 'DD-MM-YYYY', '<?=date("d-m-Y",$row['showtime']);?>')</script></td><td> at
											<input name="showtime_time!" type="text" class="form_text" size="9" value="<?=date("g:i A",$row['showtime']);?>"></td></tr></table>
											<!-- end -->
									
									
									</td>
                                  </tr>
                                   
                          

                                  
                                  
                                  <tr>
                                    <td align="right"><b>Still On?</b></td>
                                    <td>
									
											<select name="stillon"><option value="1" <?=selected($row['stillon'],1)?>>Yes</option><option value="0" <?=selected($row['stillon'],0)?>>No</option></select>
									
									</td>
                                  </tr>
                                  
                                  <tr>
                                    <td align="right"><b>Pre-Show Announcements</b></td>
                                    <td>
									
											<textarea name="preshow_comments" rows="4" cols="60"><?=$row['preshow_comments']?></textarea>
									
									</td>
                                  </tr>
                                  
                                  <tr>
                                    <td align="right"><b>Post-Show Comments</b></td>
                                    <td>
									
											<textarea name="postshow_comments" rows="4" cols="60"><?=$row['postshow_comments']?></textarea>
									
									</td>
                                  </tr>
                             
                                  
                                  
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Update">&nbsp;<input name="Button" type="button" class="form_button_red" value="Delete" onClick="JavaScript:location='delete.php?table=showings&col=showing_id&id=<?=$row['showing_id']?>';">&nbsp;<a href="showings.php">Back</a></td>
                                  </tr> 
                     		 	</form>
					  <?
                        }
					  }
					  mysql_free_result($result);
					  }
					  ?>
					  
                	</table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
        
 <? if (!$edit) { ?>
              <tr>
                <td>
                  <table width="595" border="0" cellpadding="0" cellspacing="0">
				  <form name="form2" method="post" action="save.php" enctype="multipart/form-data">
       				  <input type="hidden" name="table" value="showings">
                      <input type="hidden" name="return" value="<? if ($setup) {?>mailings.php?setup=y&new=y&type=showing&showing_id=%id%<? } else { ?>showings.php<? } ?>">
                    
                    <td style="padding-bottom:10px;">
                    <span class="page_head">When will it be?</span>
                </td>
                    
                    <tr>
                      <td><table border="0" cellspacing="1" cellpadding="4">
                          		<tr>
                                    <td align="right"><b>Feature</b></td>
                                    <td>
                                    
                                    <?=get_movie_name($setup_mov)?><input type="hidden" name="feature_movie_id" value="<?=$setup_mov?>">
                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Date/Time</b></td>
                                    <td>
									
											<!-- select date and time -->
											<table border=0 cellpadding=1><tr><td><script>DateInput('showtime_date', true, 'YYYY-MM-DD')</script></td><td> at
											<input name="showtime_time!" type="text" class="form_text" size="9" value="8:00 PM"></td></tr></table>
											<!-- end -->
									
									</td>
                                  </tr>
                          <tr>
                                    <td align="right"><b>Chapter</b></td>
                                    <td>
									
									<?=get_chapter_name(get_location_chapter($setup_loc))?><input type="hidden" name="chapter_id" value="<?=get_location_chapter($setup_loc)?>">
									
									
									
                                    </td>
                                  </tr>

                         		  <tr>
                                    <td align="right"><b>Location</b></td>
                                    <td>
									
									<?=get_location_name($setup_loc)?><input type="hidden" name="location_id" value="<?=$setup_loc?>">
									
									</td>
                                  </tr>
                                  
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="<? if ($setup) { ?>Next<? } else { ?>Add New<? } ?>">&nbsp;</td>
                                  </tr> 
                         
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
					</form>
                  </table>
                </td>
              </tr>
              <? } ?>
              
            </table>
<? require_once("inc_bot.php"); ?>
