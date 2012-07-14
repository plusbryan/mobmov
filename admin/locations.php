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
$id = $_GET['id'];
$setup = $_REQUEST['setup'];
$setup_mov = $_REQUEST['setup_mov'];

if (!$id) {
	$sql = "SELECT * FROM chapters_locations WHERE chapter_id =" . $chaptersql . " ORDER BY chapter_id ASC,loc_name ASC";
    $show_all = true;
} else {
	$sql = "SELECT * FROM chapters_locations WHERE chapter_location_id ='$id'";
}


?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0">
			
			<? if (!$setup) { 
					$result = mysql_query($sql);
			?>

              <tr>
                <td valign="top">
                <span class="page_head">Locations</span><br>
                <span class="page_subhead">List the locations where you will show here. Remember to get permission for locations that you don't own.</span><br><hr size="1" width="100%" noshade color="#dddddd"><br>
                	
                    <table border="0" cellpadding="4" cellspacing="0">
					<?
					if ($show_all) {
                    ?>
                    	<tr>
                      		<td class="line-top">Name</b></td>
                      		<td class="line-top"><b>Map</b></td>
							<? if($global_superuser){ ?><td  class="line-top"><b>Chapter</b></td><? } ?>
                      		<td class="line-top">&nbsp;</td>
					 	 </tr>
                        
					<?
                    
					}
					
					while($row = mysql_fetch_array($result)){ 
						foreach($row AS $key => $val){ 
							$key = strtolower($key);
							$$key = $val; 
						}
                        
                        if ($show_all) {
					?>     
                          	  <tr>
                                <td class="line"><?=$loc_name?></td>
                                <td class="line"><a href="<?=$loc_map?>" target="_blank"><u>Link</u></a></td>
								 <? if($global_superuser){ ?><td class="line"><?=get_chapter_name($chapter_id)?></td><? } ?>
                                <td class="line">
                                <input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='locations.php?id=<?=$chapter_location_id?>';">&nbsp;&nbsp;
                                <input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=chapters_locations&col=chapter_location_id&id=<?=$chapter_location_id?>';">&nbsp;&nbsp;
                               
                                </td>
                              </tr>
					  <?
                      	} else {
                        	$edit = true;
                        ?>	
                        
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="chapter_location_id">
                                    <input type="hidden" name="chapter_location_id" value="<?=$chapter_location_id?>">
                                    <input type="hidden" name="table" value="chapters_locations">
                                    <input type="hidden" name="return" value="locations.php">
                                    
                                    
                                    
                                    <tr>
                                     <td align="right" valign="top"><b>Chapter</b></td>
                                    <td><select name="chapter_id">
                                            <?=chapter_list($chapter_id);?>
    									</select>
                                  
                                    </td>
                                  </tr>
                                  
                                  <tr>
                                    <td align="right" valign="top"><b>Name</b></td>
                                    <td><input name="loc_name" type="text" class="form_text" size="45" value="<?=$loc_name?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"><b>Map URL</b></td>
                                    <td><input name="loc_map" type="text" class="form_text" size="45" value="<?=$loc_map?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"><b>Radio Station</b></td>
                                    <td><input name="loc_radio" type="text" class="form_text" size="45" value="<?=$loc_radio?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"><b>Instructs</b></td>
                                    <td><textarea name="loc_instructs" cols="85" rows="10" class="form_text"><?=$loc_instructs?></textarea></td>
                                  </tr>
                                  
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><br><br><input name="Submit" type="submit" class="form_button" value="Update">&nbsp;<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=chapters_locations&col=chapter_location_id&id=<?=$chapter_location_id?>';">&nbsp;<a href="locations.php">Back</a></td>
                                  </tr> 
                     		 	</form>
					  <?
                        }
					  }
					  mysql_free_result($result);
					  ?>
					  
                	</table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
			  
				<? } else { ?>
				
				 <tr>
                      <td><span class="page_head">Where will it be?</span>
					 </td>
              </tr>
			  
			  <tr>
                <td><?=roundedbox(TOP,"d4ebff","8");?> Choose the showing location, or enter a new one below:<br><form name="form2" method="post" action="showings.php?setup=y&setup_mov=<?=$setup_mov?>" enctype="multipart/form-data">
       				 
                                    
                     <select name="setup_loc">
                                    <option value="" selected></option>
                                    <?
                                    $sql2 = "SELECT chapter_location_id,loc_name,chapter_id FROM chapters_locations WHERE chapter_id = $chaptersql ORDER BY chapter_id ASC,loc_name ASC";
                                    $result2 = mysql_query($sql2);
                                    while($row2 = mysql_fetch_array($result2)){ 
                						foreach($row2 AS $key2 => $val2){ 
                							$key2 = "it_".strtolower($key2);
                							$$key2 = $val2; 
                						}
										?>
										<option value="<?=$it_chapter_location_id?>" <? if ($showing_location_id == $it_chapter_location_id) {?>selected<? } ?>><?=$it_loc_name?> (<?=get_chapter_name($it_chapter_id)?>)</option>
                             <? }
									mysql_free_result($result2);
									?>

									</select>&nbsp;<input name="Submit" type="submit" class="form_button_big" value="Next"><br/>
                                    </form><?=roundedbox(BOTTOM);?><br>
									
                                    
                     </td>
              </tr>
				
				 <tr>
                <td>
				
					<form name="form2" method="post" action="save.php" enctype="multipart/form-data">
       				  <input type="hidden" name="return" value="showings.php?setup=y&setup_mov=<?=$setup_mov?>&setup_loc=%id%">
							<input type="hidden" name="table" value="chapters_locations">
							<input type="hidden" name="chapter_id" value="<?=$global_chapter_id?>">
                  <table border="0" cellspacing="1" cellpadding="1">
                          		
                          		
                          		 <tr>
                                     <td align="right" valign="top"><b>Chapter</b></td>
                                    <td><select name="chapter_id">
                                            <?=chapter_list($chapter_id);?>
    									</select>
                                  
                                    </td>
                                  </tr>
                                  
							  <tr>
								<td align="right" valign="top"><b>Location Name</b></td>
								<td style="color:#999999;"><input name="loc_name" type="text" class="form_text" size="45" value=""><br>Usually a nickname, but can also be cross-streets if desired</td>
							  </tr>
							  <tr>
								<td align="right" valign="top"><b>Map URL</b></td>
								<td style="color:#999999;"><input name="loc_map" type="text" class="form_text" size="45" value="http://">
										<br>Recommended: <a href="http://maps.google.com" target="_blank">Google Maps</a></td>
							  </tr>
							  <tr>
								<td align="right" valign="top"><b>Radio</b></td>
								<td style="color:#999999;"><input name="loc_radio" type="text" class="form_text" size="45" value="88.3FM"><br>The station you will usually set your radio tuner for at this location.</td>
							  </tr>
							  <tr>
								<td align="right" valign="top"><b>Instructions</b></td>
								<td style="color:#999999;"><textarea name="loc_instructs" cols="55" rows="10" class="form_text"></textarea><br>How to get there, etc</td>
							  </tr>
							  
						  
							  <tr>
								<td align="right">&nbsp;</td>
								<td><br><input name="Submit" type="submit" class="form_button_big" value="Next"></td>
							  </tr> 
                         
                      </table>
					</form>
                  
                </td>
              </tr>
				
				
				<? } ?>
			  
			  
              <? if (!$edit && !$setup) { ?>
              <tr>
                <td>
                  <table width="595" border="0" cellpadding="0" cellspacing="0">
				  <form name="form2" method="post" action="save.php" enctype="multipart/form-data">
       				  <input type="hidden" name="return" value="locations.php">
                                    <input type="hidden" name="table" value="chapters_locations">
                                    
                                    <input type="hidden" name="chapter_id" value="<?=$global_chapter_id?>">
                    <tr>
                      <td><span class="font-blue">&raquo; Add New Location</span></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table border="0" cellspacing="1" cellpadding="1">
                          		
                                  
                                  <tr>
                                    <td align="right"><b>Name</b></td>
                                    <td><input name="loc_name" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Map URL</b></td>
                                    <td><input name="loc_map" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Radio Station</b></td>
                                    <td><input name="loc_radio" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Instructs</b></td>
                                    <td><textarea name="loc_instructs" cols="85" rows="15" class="form_text"></textarea></td>
                                  </tr>
                                  
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Save As New"></td>
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