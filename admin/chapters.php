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
		
		
$action = $_GET['action'];
$chapter_id = $_GET['id'];
if ($action == "approve") {
	if(!$global_superuser){
    	$status_message = "You can't do that.";
    } else {
    	
    	// GET CHAPTER INFO
    	$sql = "SELECT * FROM chapters LEFT JOIN members ON members.member_id = chapters.coord_id WHERE chapters.chapter_id = '$chapter_id'";
    	$result = mysql_query($sql);
        $row = mysql_fetch_array($result); 
        foreach($row AS $key => $val){ 
        	$key = "approve_".strtolower($key);
        	$$key = $val; 
        }
		mysql_free_result($result);
        
        if (!$approve_username){
        	// find user by email address
            /*
        	$sql = "SELECT user_id FROM phpbb_users WHERE user_email='$approve_email'";
	    	$result = mysql_query($sql);
	        if ($result && mysql_num_rows($result) > 0) {
	        	$forum_user_id = mysql_result($result,0,0);
	        }
	        // create new group link to drivers group for this user
	        if ($forum_user_id) {
	        	$sql = "DELETE FROM phpbb_user_group WHERE user_id='$forum_user_id' LIMIT 1";
	    		$result = mysql_query($sql);
	        	$sql = "INSERT INTO phpbb_user_group SET user_id='$forum_user_id',group_id='3'";
	    		$result = mysql_query($sql);
	        }
            */
        } else {
	        // SET UP USERNAME AND PASSWORD FOR DRIVER
            /*
	    	$phpbb_root_path = '../../forum.mobmov.org/';
	
	    	$phpEx='php';
			define('IN_PHPBB', true);
			include($phpbb_root_path . 'extension.inc');
			include($phpbb_root_path . 'common.'.$phpEx);
			include($phpbb_root_path . 'includes/sql_parse.' . $phpEx);
			include($phpbb_root_path.'includes/functions_mod_user.php'); // WARNING: conflicts with mysql connection!!
			$username = $approve_username;
			$user_password = "driver";
			$user_email = $approve_email;
			$group_id = 3;
			insert_user($username, $user_password, $user_email, $group_id);
            */
        }
        
		//SET APPROVED
        $sql = "UPDATE chapters SET approved='y' WHERE chapter_id=$chapter_id LIMIT 1";
    	mysql_query($sql);
        $sql = "UPDATE members SET access='y' WHERE member_id=$approve_member_id LIMIT 1";
    	mysql_query($sql);
        
    	// SEND APPROVED EMAIL
        $to = $approve_email.",info@mobmov.org"; 
        $from = "info@mobmov.org";
        $subject = "Welcome aboard!";
        $body = "
CONGRATS! Your chapter has been approved. If you haven't already, quickly start setting up your kit so you can start shows.\n\n
The driver control panel is located at: http://www.mobmov.org/admin\n
Your username is '$approve_email' and your password is the one you chose during setup.\n\n
The forum is located at: http://forum.mobmov.org/\n
Your forum username is '$approve_username' and your password is 'driver'. Please change this in the forum when you first log in.\n\n
You can log in now to download the manifesto (instructions for firing up your mobmov chapter), video titles for your use, and access your mailing list.\n\n
If you would like to be featured on our home page, please send us a profile picture and a brief (1 paragraph) bio about yourself and your chapter.\n\n
The MobMov.org Admin Team\n\n";        
    	email($to,$subject,stripslashes($body));
    
    }
} else if ($action == "deny") {
	if(!$global_superuser){
    	$status_message = "You can't do that.";
    } else {
    	$sql = "SELECT * FROM chapters LEFT JOIN members ON members.member_id = chapters.coord_id WHERE chapters.chapter_id = '$chapter_id' LIMIT 1";
    	$result = mysql_query($sql);
        $row = mysql_fetch_array($result); 
        foreach($row AS $key => $val){ 
        	$key = "approve_".strtolower($key);
        	$$key = $val; 
        }
		mysql_free_result($result);
        
    	$sql = "DELETE FROM chapters WHERE chapter_id = '$chapter_id'";
    	mysql_query($sql);
        $sql = "DELETE FROM members WHERE member_id = '$approve_member_id'";
    	mysql_query($sql);
        
        $to = $approve_email;
        $from = "info@mobmov.org";
        $subject = "Sorry, we can't accept you at this time";
        $body = "
I'm sorry, but we cannot accept your proposal for a new mobmov at this time. The most likely reason is that we just don't think your location is far enough away from an existing mobmov. Please check our list of mobmovs and contact the driver of the one nearest you and offer your hand: http://www.mobmov.org/venues.php
Good luck, and please let us know if we can be of any help!\n\n
The MobMov.org Admin Team\n\n";        
    	email($to,$subject,stripslashes($body));
        $chapter_id="";
    }
} else if ($action == "accepting") {

	$accepting = $_GET['accepting'];
	$chapter_id = $_GET['chapter_id'];
    
    $sql = "UPDATE chapters SET accepting='$accepting' WHERE chapter_id='$chapter_id' LIMIT 1";
	$success = mysql_query($sql);
    if ($accepting == "Y") {
    	$canyou = "Your chapter can accept members now.";
    } else {
    	$canyou = "We've removed your chapter from the list. Just tell us when you're ready.";
    }
    if ($success) { $status_message = $canyou." If you made a mistake, you can <a href='chapters.php?action=accepting&accepting=N&id=$chapter_id'>undo</a>."; }
}

if (!$chapter_id) {
	if($global_superuser){
		$sql = "SELECT * FROM chapters LEFT JOIN members ON members.member_id = chapters.coord_id ORDER BY approved DESC,chapters.country DESC,chapters.state, chapters.city";
	} else {
		$sql = "SELECT * FROM chapters LEFT JOIN members ON members.member_id = chapters.coord_id WHERE chapters.chapter_id = $chaptersql ORDER BY rank ASC,approved,chapters.state, chapters.city";
   	}
    $result = mysql_query($sql);
    $chapter_count = mysql_num_rows($result);
    if ($chapter_count == 1) {
    	$show_all = false;
    } else {
    	$show_all = true;
    }
	
} else {
    if($global_superuser){
    	//$sql = "SELECT count(chapter_id) FROM chapters";
		//$result = mysql_query($sql);
    	//$chapter_count = mysql_result($result,0,0);
    }
    $sql = "SELECT *,UNIX_TIMESTAMP(created) as created,UNIX_TIMESTAMP(started) as started FROM chapters WHERE chapter_id=$chapter_id";
    $result = mysql_query($sql);
}


require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="2">

              <tr>
                <td valign="top">
                <span class="page_head">Chapter Info</span><br>
                <span class="page_subhead">Edit information about your chapter here.</span><br><hr size="1" width="100%" noshade color="#dddddd"><br>
                	
                	<table border="0" cellpadding="4" cellspacing="0">
					<?
					if ($show_all) {
                    ?>
                    	<tr>
                      		
                      		<td class="table_header"><b>Location</b></td>
								<td class="table_header">
	                             <? if($global_superuser){ ?>
	                            <b>Coordinator</b>
	                            <? } ?>
								</td>
                            <td class="table_header"><b>Count</b></td>
                            <td class="table_header"><b>Accepting?</b></td>
                            <td class="table_header"><b>New?</b></td>
                            <td class="table_header"><b>Approval?</b></td>
                      		<td>&nbsp;</td>
					 	 </tr>
                        
					<?
                    
					}
					$last_country="";
					while($row = mysql_fetch_array($result)){ 
                    	extract($row, EXTR_PREFIX_ALL, "chapterinfo");
                        if ($show_all) {
                        	
					?>     
					<?
					if ($chapterinfo_country !== $last_country) {
						print "<tr><td class='table_header_sub' colspan=6><b>$chapterinfo_country</b></td><td></td></tr>";
						$last_country = $chapterinfo_country; 
					}
					
					?>
                          	  <tr>
                                <td style="background-color:#eeeeee"><nobr><?=$chapterinfo_city?><?if ($chapterinfo_state) {?>, <?=$chapterinfo_state?><? } ?></nobr></td>
									<td style="background-color:#eeeeee">
	                                <? if($global_superuser){ ?>
	                                	<a href="members.php?id=<?=$chapterinfo_coord_id?>"><?=$chapterinfo_name?></a>
	                                <? } ?>
									</td>
                                <td style="background-color:#eeeeee" align="center"><?=countMembers($chapterinfo_chapter_id)?></td>
                                <td style="background-color:#eeeeee" align="center"><? if($chapterinfo_accepting == "y"){print "x";}?></td>
                                <td style="background-color:#eeeeee" align="center"><? if($chapterinfo_new == "y"){print "x";}?></td>
                                <td style="background-color:#eeeeee" align="center"><? if($chapterinfo_approved == "n"){print "x";}?></td>
                                <td>
                                	<input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='chapters.php?id=<?=$chapterinfo_chapter_id?>';">&nbsp;&nbsp;
                                </td>
                              </tr>
					  <?
                      	} else {
                        	$edit=true;
                        ?>	
                        
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="chapter_id">
                                    <input type="hidden" name="chapter_id" value="<?=$chapterinfo_chapter_id?>">
                                    <input type="hidden" name="table" value="chapters">
                                    <input type="hidden" name="return" value="chapters.php">
                                  
                                 <!-- <tr>
                                    <td align="right"><b>Title</b></td>
                                    <td>MobMov-<input name="title" type="text" class="form_text" size="45" value="<?=$chapter_title?>"></td>
                                  </tr>-->
                                  <tr>
                                    <td width="50" align="right"><b>Location</b></td>
                                    <td valign="top"><tooltip title="where you show. if you need to edit this, let us know."><img src="images/question.gif" width="16" height="16" align="absmiddle"></tooltip></td>
                                    <td>
                                    <? if($global_superuser){ ?>
                                    	<input name="city" type="text" class="form_text" size="30" value="<?=$chapterinfo_city?>">&nbsp;<input name="state" type="text" class="form_text" size="4" value="<?=$chapterinfo_state?>"><br><input name="country" type="text" class="form_text" size="25" value="<?=$chapterinfo_country?><? if (!$chapterinfo_country) {print "United States";} ?>">
                                    <? 
                                 
                                    } else {
                                    	print get_chapter_name($chapterinfo_chapter_id);
                                     } ?></td>
                                    
                                  </tr>
                                  <? if($global_superuser){ ?>
                                          <tr>
                                            <td align="right"><b>Rank</b></td>
                                            <td></td>
                                            <td>
                                            <select name="rank" class="form_text">
                                            	<option value="99">normal</option>
                                                <option value="1">super-top</option>
                                                <option value="2">top</option>
                                            </select>
                                            
                                            </td>
                                          </tr>
                                 	<? } ?>
                                    
                                    
                                  <tr>
                                    <td align="right" valign="top"><b>Accepting Members?</b></td>
                                    <td valign="top"><tooltip title="<? if ($chapter_accepting == "y") {?>setting this value to no will remove your chapter from the home page, but will not remove existing members on your list<? } else { ?>set this value to <b>yes</b> to put your chapter on the home page to start getting signups. please only do this when you are about ready to do a show.<? } ?>"><img src="images/question.gif" width="16" height="16" align="absmiddle"></tooltip></td>
                                    <td><select name="accepting">
                                    
										<option value="Y" <? if ($chapterinfo_accepting == "y") {?>selected<? } ?>>Yes</option>
                                        <option value="N" <? if ($chapterinfo_accepting != "y") {?>selected<? } ?>>No</option>
                                  

									</select></td>
                                  </tr>
                                  <? if($global_superuser){ ?>
                                          <tr>
                                            <td align="right" valign="top"><b>List as New</b></td>
                                            <td></td>
                                            <td><select name="new">
                                            
        										<option value="Y" <? if ($chapterinfo_new == "y") {?>selected<? } ?>>Yes</option>
                                                <option value="N" <? if ($chapterinfo_new != "y") {?>selected<? } ?>>No</option>
                                          
        
        									</select></td>
                                          </tr>
                                  <? } ?>
                                  
                                  <? if($global_superuser){ ?>
                                         <tr>
                                    <td align="right" valign="top"><b>Approved?</b></td>
                                    <td valign="top"></td>
                                    <td><select name="approved">
                                    
										<option value="Y" <? if ($chapterinfo_approved == "y") {?>selected<? } ?>>Yes</option>
                                        <option value="N" <? if ($chapterinfo_approved != "y") {?>selected<? } ?>>No</option>
                                  

									</select></td>
                                          </tr>
                                  <? } ?>
                                  
                                  <? if($global_superuser){ ?>
                                          <tr>
                                            <td align="right" valign="top"><b>Since</b></td>
                                            <td></td>
                                            <td><?=date("m/d/y",$chapterinfo_started)?> last updated: <?=date("m/d/y",$chapterinfo_created)?></td>
                                          </tr>
                                  <? } ?>
                                  
                                  
                                  <? if($global_superuser){ ?>
                                          <tr>
                                            <td align="right" valign="top"><b>Coordinator</b></td>
                                            <td></td>
                                            <td><select name="coord_id">
                                            
                                            <?
                                            $sql2 = "SELECT member_id,name FROM members";
                                            $result2 = mysql_query($sql2);
                                            while($row2 = mysql_fetch_array($result2)){ 
                        						foreach($row2 AS $key2 => $val2){ 
                        							$key2 = "it_".strtolower($key2);
                        							$$key2 = $val2; 
                        						}
        										?>
        										<option value="<?=$it_member_id?>" <? if ($chapterinfo_coord_id == $it_member_id) {?>selected<? } ?>><?=$it_name?></option>
                                    <? } 
									mysql_free_result($result2);?>
        
        									</select> <a href="members.php">Edit</a></td>
                                          </tr>
                                  <? } ?>
                                  
                                  <tr>
                                    <td align="right" valign="top"><b>About</b></td>
                                    <td valign="top"><tooltip title="enter a little something about your chapter. this will be displayed on the venues page"><img src="images/question.gif" width="16" height="16" align="absmiddle"></tooltip></td>
                                    <td>
									
									
												
													<!-- Include the Free Rich Text Editor Runtime -->
													<script src="common/richtext.js" type="text/javascript" language="javascript"></script>
													<!-- Include the Free Rich Text Editor Variables Page -->
													<script src="common/config.js" type="text/javascript" language="javascript"></script>
													<!-- Initialise the editor -->
													<script>
													initRTE('<?=$chapterinfo_intro?>');
													</script>
													
												


									
									<!--<textarea name="intro" cols="85" rows="10" class="form_text"><?=$chapterinfo_intro?></textarea>
									
									
									<br>(can use simple html)--></td>
                                  </tr>
                                  
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td></td>
                                    <td><? if ($chapterinfo_approved == "n") {?>
                                    <textarea name="" cols="85" rows="10" class="form_text" disabled><?=$chapterinfo_whyhere?></textarea><br>
                                    <textarea name="" cols="85" rows="10" class="form_text" disabled><?=$chapterinfo_whyyou?></textarea><br>
                                    <input name="Button" type="button" class="form_button" value="Approve" onClick="JavaScript:location='chapters.php?id=<?=$chapterinfo_chapter_id?>&action=approve';"> &nbsp; <input name="Button" type="button" class="form_button" value="Deny" onClick="JavaScript:location='chapters.php?id=<?=$chapterinfo_chapter_id?>&action=deny';"><br><? } ?>
                                    <br><input name="Submit" type="submit" class="form_button" value="Update"><? if($global_superuser){ ?>&nbsp;<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=chapters&col=chapter_id&id=<?=$chapterinfo_chapter_id?>';">&nbsp;<a href="chapters.php">Back</a><? } ?></td>
                                  </tr> 
                     		 	</form>
                     		 	<tr><td colspan=3></td></tr>
                     		 	<? 

    $sql3 = "SELECT m.name,m.email,m.member_id FROM chapters_drivers as cd LEFT JOIN members as m ON m.member_id = cd.driver_id where cd.chapter_id='$chapterinfo_chapter_id' ORDER BY name";
    $result3 = mysql_query($sql3);
    $chapter_drivers_count = mysql_num_rows($result3);
    if ($chapter_drivers_count > 0) {
    	?>
    	<tr>
    	<td align="right" valign="top"><b>Other drivers</b></td>
        <td></td><td>
    	<?
    	while($driver3 = mysql_fetch_array($result3)){
    		$driver_name = $driver3['name'];
    		$driver_email = $driver3['email'];
    		$driver_member_id = $driver3['member_id'];
    	?>
    		<a href="mailto:<?=$driver_email?>"><?=$driver_name?></a> <a href="delete.php?id=<?=$chapterinfo_chapter_id?>&table=chapters_drivers&col=chapter_id&col2=driver_id&id2=<?=$driver_member_id?>"><font color="black">x</font></a><br/>
    	<?
    	}
		
    	?>
    	</td></tr>
    	<?
    }
	mysql_free_result($result3);
    ?>
                     		 	<form action="save.php" method="post" enctype="multipart/form-data" name="ADD_DRIVER_TO_CHAPTER">
                                    <input type="hidden" name="chapter_id" value="<?=$chapterinfo_chapter_id?>">
                                    <input type="hidden" name="table" value="chapters_drivers">
                                    <input type="hidden" name="return" value="chapters.php?id=<?=$chapterinfo_chapter_id?>">
                                    
                                    <tr><td>Add driver</td>
                                    <td></td>
                                    <td>
	                                    <select name="driver_id">
	                                            
	                                         <?
	                                         $sql32 = "SELECT member_id,name FROM members ORDER BY name";
	                                         $result32 = mysql_query($sql32);
	                                         while($row32 = mysql_fetch_array($result32)){ 
	                     						foreach($row32 AS $key32 => $val32){ 
	                     							$key32 = "itm_".strtolower($key32);
	                     							$$key32 = $val32; 
	                     						}
	     										?>
	     										<option value="<?=$itm_member_id?>"><?=$itm_name?></option>
	                                 <? } 
									 mysql_free_result($result32);?>
	     
	     								</select> <input name="Submit" type="submit" class="form_button" value="Add">
                                    </td><td></td></tr>
                                    
                                 </form>
					  <?
                        }
					  }
					  ?>
					  
                	</table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <? if($global_superuser){ 
                   if (!$edit) { ?>
                  <tr>
                    <td>
                      <table border="0" cellpadding="0" cellspacing="0">
    				  <form name="form2" method="post" action="save.php" enctype="multipart/form-data">
           				  <input type="hidden" name="return" value="chapters.php">
                                        <input type="hidden" name="table" value="chapters">
                        <tr>
                          <td><span class="font-blue">&raquo; Add New Chapter </span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td><table border="0" cellspacing="1" cellpadding="1">
                          		
                                  
                                  <tr>
                                    <td align="right"><b>Title</b></td>
                                    <td><input name="title" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Location</b></td>
                                    <td><input name="city" type="text" class="form_text" size="40" value="">&nbsp;<input name="state" type="text" class="form_text" size="4" value=""><br><input name="country" type="text" class="form_text" size="40" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Accepting</b></td>
                                    <td><select name="accepting">
                                    
										<option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                  

									</select></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Coordinator</b></td>
                                    <td><select name="coord_id">
                                    
                                    <?
                                    $sql2 = "SELECT member_id,name FROM members ORDER BY name";
                                    $result2 = mysql_query($sql2);
                                    while($row2 = mysql_fetch_array($result2)){ 
                						foreach($row2 AS $key2 => $val2){ 
                							$key2 = "it_".strtolower($key2);
                							$$key2 = $val2; 
                						}
										?>
										<option value="<?=$it_member_id?>"><?=$it_name?></option>
                             <? } 
							 mysql_free_result($result2);?>

									</select></td>
                                  </tr>
                                  
                                  <tr>
                                    <td align="right"><b>About</b></td>
                                    <td><textarea name="intro" cols="85" rows="10" class="form_text"></textarea></td>
                                  </tr>
                                  
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Update">&nbsp;<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=showings&col=showing_id&id=<?=$chapter_showing_id?>';"></td>
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
              	<? } 
               } ?>
            </table>
        
        
        <? 
		mysql_free_result($result);
		require_once("inc_bot.php"); ?>