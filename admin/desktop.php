<? require_once("inc_functions.php");
if(!$global_islogged){
	header("Location: index.php");
	exit;
}
require_once("inc_top.php"); 
?>

Welcome, <?=$global_name?>! &nbsp;<br>
<?

 	if($global_superuser){ 
		$sql ="SELECT count(*) FROM mailinglist";
	} else {
		$sql ="SELECT count(*) FROM mailinglist AS m CROSS JOIN mailinglist_chapters AS c USING (member_id) WHERE c.chapter_id = $chaptersql";
    }
    db_connect();
    $result = mysql_query($sql);
    $count =0;
    $count = mysql_result ($result, 0);
	mysql_free_result($result);
    
    
    // info about this user's chapter
    if(!$global_superuser){ 
		$sql ="SELECT accepting,chapter_id,city FROM chapters WHERE chapter_id = $chaptersql";
		$result = mysql_query($sql);
    	$accepting = mysql_result ($result, 0,0);
        $chapter_id = mysql_result ($result, 0,1);
        $city = mysql_result ($result, 0,2);
    } else {
		$SQL ="SELECT COUNT(DISTINCT email) AS count FROM mailinglist";
		$result = mysql_query($SQL);
		$numusers = mysql_result($result,0,0);
    }
	mysql_free_result($result);

?>
<? if ($accepting == "y") { ?>
	<br>You have <?=$count?> subscribers for your chapter.
<? } else if (!$global_superuser) { ?>
	<div class="message"><font color="red"><b>Note: </b></font> Your chapter in <?=$city?> is not accepting members at this time. If you're ready to start accepting members, click <a href="chapters.php?action=accepting&accepting=Y&chapter_id=<?=$chapter_id?>">here</a>.</div>
<? } else { ?>
	There are <?=$numusers?> subscribers in the lists.
<? } ?>
<br><br>
<div style="text-align:center;">           
<table border=0 cellpadding=4 width="500"><tr>

<td>

    <!-- BUTTON -->
    <div onClick="JavaScript:location='movies.php?setup=y';" class="main_button">
		<?=roundedbox(TOP,"ffd08e","8","120");?>
        	<img src="images/camera.gif"><br><br>Set up Showing
		<?=roundedbox(BOTTOM);?>
    </div> 
    <!-- END BUTTON -->

</td>

<td>
	
	<!-- BUTTON -->
    <div onClick="JavaScript:location='locations.php';" class="main_button">
		<?=roundedbox(TOP,"d4ebff","8","120");?>
        	<img src="images/location.gif"><br><br>Edit Locations
		<?=roundedbox(BOTTOM);?>
    </div> 
    <!-- END BUTTON -->
    

</td>

<td>
	
	<!-- BUTTON -->
    <div onClick="JavaScript:location='mailings.php?new=y&type=poll';" class="main_button">
		<?=roundedbox(TOP,"d4ebff","8","120");?>
        	<img src="images/vote.gif"><br><br>Request Vote
		<?=roundedbox(BOTTOM);?>
    </div> 
    <!-- END BUTTON -->

</td>
</tr><tr>
<td>
	
	<!-- BUTTON -->
    <div onClick="JavaScript:location='files.php';" class="main_button">
		<?=roundedbox(TOP,"d4ebff","8","120");?>
        	<img src="images/data.gif"><br><br>Video &amp; Posters
		<?=roundedbox(BOTTOM);?>
    </div> 
    <!-- END BUTTON -->

</td>

<td>
	
	<!-- BUTTON -->
    <div onClick="JavaScript:location='http://community.mobmov.org/';" class="main_button">
		<?=roundedbox(TOP,"d4ebff","8","120");?>
        	<img src="images/type.gif"><br><br>Forum
		<?=roundedbox(false);?>
    </div> 
    <!-- END BUTTON -->

</td>

<td>
	
	<!-- BUTTON -->
    <div onClick="document.getElementById('changepwd').style.display='block';" class="main_button">
		<?=roundedbox(TOP,"d4ebff","8","120");?>
        	<img src="images/change-password.gif" width="30" height="30"><br><br>Change Password
		<?=roundedbox(false);?>
    </div> 
    <!-- END BUTTON -->

</td>

</tr><tr>


<td colspan=3>

  
<div id="changepwd" style="display:none">
	<form action="index.php" method="post">
	<input type="hidden" name="resetpass" value="<?=$global_email?>">
	<b>Change password to:</b> <input type="password" name="resetpass_to" value=""> <input type="submit" name="Change" value="Change">
	</form>
	<br><br>
</div>  

</td>



</tr>
</table>
</div>


<? require_once("inc_bot.php"); ?>