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
$member_id = $_GET['id'];
if (!$member_id) {
	$sql = "SELECT * FROM members ORDER BY name";
    $show_all = true;
} else {
	$sql = "SELECT * FROM members WHERE member_id=$member_id";
}
$result = mysql_query($sql);

?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="2">

              <tr>
                <td valign="top">
                	<table border="0" cellpadding="4" cellspacing="0">
					<?
					if ($show_all) {
// LIST
                    ?>
                    	<tr>
                      		<td class="table_header"><b>Name</b></td>
                      		<td class="table_header"><b>Email</b></td>
                      		<td class="table_header"><b>Phone</b></td>
                            <td class="table_header"><b>Access</b></td>
                            <td class="table_header"><b>Superuser</b></td>
                      		<td class="table_header">&nbsp;</td>
                            
					 	 </tr>
                        
					<?
                    
					}
					
					while($row = mysql_fetch_array($result)){ 
						foreach($row AS $key => $val){ 
							$key = "it_".strtolower($key);
							$$key = $val; 
						}
                        
                        if ($show_all) {
					?>     
                          	  <tr>
                                <td><?=$it_name?></td>
                                <td><a href="mailto:<?=$it_email?>"><u><?=$it_email?></u></a></td>
                                <td><?=$it_member_phone?></td>
                                <td align="center"><? if($it_access == "y"){print "&middot;";}?></td>
                                <td align="center"><? if($it_superuser == "y"){print "&middot;";}?></td>
                                <td>
                                	<nobr><input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='members.php?id=<?=$it_member_id?>';">&nbsp;&nbsp;
                                	<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=members&col=member_id&id=<?=$it_member_id?>';">&nbsp;&nbsp;
                               </nobr>
                                </td>
                              </tr>
					  <?
                      	} else {
// EDIT
                        	$edit=true;
                        ?>	
                        
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="member_id">
                                    
                                    <input type="hidden" name="member_id" value="<?=$it_member_id?>">
                                    <input type="hidden" name="table" value="members">
                                    <input type="hidden" name="return" value="members.php">
                                  <tr>
                                    <td align="right"><b>ID</b></td>
                                    <td><?=$it_member_id?></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Name</b></td>
                                    <td><input name="name" type="text" class="form_text" size="45" value="<?=ucwords(strtolower($it_name))?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Forum Username</b></td>
                                    <td><input name="username" type="text" class="form_text" size="45" value="<?=$it_username?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Password</b></td>
                                    <td><input name="old_password" type="hidden" class="form_text" size="45" value="<?=$it_password?>">
                                    <input name="*password" type="password" class="form_text" size="45" value="<?=$it_password?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Email</b></td>
                                    <td><input name="email" type="text" class="form_text" size="45" value="<?=strtolower($it_email)?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Phone</b></td>
                                    <td><input name="member_phone" type="text" class="form_text" size="45" value="<?=strtolower($it_member_phone)?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Access</b></td>
                                    <td><select name="access">
                                    	<option value="y" <? if ($it_access == "y") {?>selected<? } ?>>Yes</option>
                                        <option value="n" <? if ($it_access == "n") {?>selected<? } ?>>No</option></select></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Superuser</b></td>
                                    <td><select name="superuser">
                                    <option value="n" <? if ($it_superuser == "n") {?>selected<? } ?>>No</option>
                                    	<option value="y" <? if ($it_superuser == "y") {?>selected<? } ?>>Yes</option>
                                    	
                                        </select>
                                   		</td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Bio</b></td>
                                    <td><?=strtolower($it_name)?> is...<br/><textarea name="bio" class="form_text" cols="45" rows="3"><?=strtolower($it_bio)?></textarea></td>
                                  </tr>
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Update">&nbsp;<input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=members&col=member_id&id=<?=$it_member_id?>';"></td>
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
              <? if (!$edit) { ?>
              <tr>
                <td>
                  <table width="595" border="0" cellpadding="0" cellspacing="0">
				  <form name="form2" method="post" action="save.php" enctype="multipart/form-data">
       				  <input type="hidden" name="return" value="members.php">
                                    <input type="hidden" name="table" value="members">
                    <tr>
                      <td><span class="font-blue">&raquo; Add New Member </span></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table border="0" cellspacing="1" cellpadding="1">
                          		
                                  
                                  <tr>
                                    <td align="right"><b>Name</b></td>
                                    <td><input name="name" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Forum Username</b></td>
                                    <td><input name="username" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Email</b></td>
                                    <td><input name="email" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Password</b></td>
                                    <td><input name="*password" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Phone</b></td>
                                    <td><input name="member_phone" type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Access</b></td>
                                    <td><select name="access">
                                    	<option value="y">Yes</option>
                                        <option value="n">No</option></select></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>Superuser</b></td>
                                    <td><select name="superuser">
                                    	<option value="n">No</option>
                                    	<option value="y">Yes</option>
                                    	
                                        </select>
                                   		</td>
                                  </tr>
                        		 <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button" value="Save New"></td>
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