<? require("common/top.php"); ?>

<? 
if ($_POST['app']) { 
	// process application
    while(list($key, $val) = each($_POST)) {
		$key = "post_".strtolower($key);
		$$key = $val;
    	//print $key."<br>";
    }
    if (!$post_name || !$post_email || !$post_member_phone || !$post_password || !$post_city) {
		$message = "<b><font color='red'>Some fields were left blank</font></b>";
		
	} else if ($post_verify != "mobmov") {
		$message = "<b><font color='red'>To prevent spam, we ask that you enter the word 'mobmov' in the bottom box. Thanks.</font></b>";
		
    } else if ($post_password != $post_password_confirm) {
    	$message = "<b><font color='red'>Your passwords did not match</font></b>";
		
    } else {
    	db_connect();
    	$sql = "INSERT INTO members SET name='$post_name',username='$post_username',member_phone='$post_member_phone',member_address='$post_member_address', password=OLD_PASSWORD('$post_password'), email='$post_email', access='n',superuser='n'";
        $result = mysql_query($sql);
        $coord_id = mysql_insert_id();
      
    	if ($post_preselect_chapter) {
        	$chapter_id = $post_preselect_chapter;
        } else {
        	$sql = "INSERT INTO chapters SET city='$post_city', state='$post_state', zip='$post_zip', title='$post_title', country='$post_country', whyhere='$post_whyhere',whyyou='$post_whyyou',approved='n',licensed='n',new='y',accepting='y',rank='99',started='".date('Y-m-d')."',coord_id='$coord_id'";
    		$result = mysql_query($sql);
        	$chapter_id = mysql_insert_id();
        }
        
        // EMAIL TO ADMIN
        $to="info@mobmov.org";
        if ($post_preselect_chapter) {
        	$subject="mobmov: EXISTING chapter driver helper application";
        	$body = "email: $post_email\n\n member: http://www.mobmov.org/admin/members.php?id=$coord_id\n\n chapter: http://mobmov.org/admin/chapters.php?id=$chapter_id";
        } else {
        	$subject="mobmov: NEW chapter application";
        	$body = "email: $post_email\n\n member: http://www.mobmov.org/admin/members.php?id=$coord_id\n\n new chapter app: http://mobmov.org/admin/chapters.php?id=$chapter_id";
        }
        
        email($to,$subject,stripslashes($body));
        
        if ($post_preselect_chapter) {
        	$to = get_chapter_driver_email($post_preselect_chapter);
        	$subject="Someone has applied to help drive your mobmov chapter (approval required)";
        	$body = "Someone in your area has said they want to help you organize events for your chapter. We will take care of approving them -- but please reply to this message if you have any objections. The new driver's email address is: $email. Feel free to email them and welcome them and start organizing!";
        	
        }
        
        
        // EMAIL TO PENDING DRIVER
        $to = $post_email;
        $subject="mobmov: thank you for your driver application";
        $body = "Thanks for your application. We'll get back to you shortly. --the mobmov";
        email($to,$subject,stripslashes($body));
        if ($post_preselect_chapter) {
        $message = "<b>Thanks for your application!</b> The lead driver of your community will review it and will get back to you shortly.<br/><br/>Until then, you could start <a href='manifesto'>setting up your kit</a>.</b>";
        } else {
        	$message = "<b>Thanks for your application!</b> We'll review it and if we're interested in expanding to your area, respond within a week.<br/><br/>Until then, you could start <a href='manifesto'>setting up your kit</a>.</b>";
        	
        }
        $done = true;
    }
}
?>

 <?
 if ($post_preselect_chapter) {
 	$preselect_chapter = $post_preselect_chapter;
 } else {
    $preselect_chapter = $_GET['preselect_chapter'];
 }
    ?>

<img src="/graphics/setupyourown.jpg" width="400" height="266" border=0 style="border:2px solid black" align="right"/>
		<span class="header-page">drive the mobmov</span><br/><br/>
		<?
		if ($message) {
			print "<div class='status_message'>".$message."</div><br/>";
		} else {
?>
MobMov chapters, or "movie mobs", are regions or cities where the mobmov exists. There can be several <b>Drivers</b> (those who show the films) for each chapter, but the first person to sign up for a particular city/region is distinguished as the <b>Lead Driver</b>. The Lead Driver gets to approve new drivers in their area. Drivers who share a chapter can plan and produce their own shows independently, or they can work together.
<br/><br/>
		Fill out the application form below to <? if ($preselect_chapter) { ?>help run the <b><?=get_chapter_name($preselect_chapter)?></b> chapter. Did you want to set up a <a href="drive.php">new chapter</a>?<? } else { ?>set up a new mobmov chapter (as a Lead Driver). Did you want to join an existing chapter? Go to <a href="venues.php">the list of venues</a> and click &quot;join as a driver&quot;.<? } ?><br/><br/>Beyond the equipment, there is no cost. You'll get your own mailing list on this site, access to our custom video titles, and free help if you need it.
        <br><br><a href="http://www.mobmov.org/manifesto"><font size=3><b>tutorial on how to set up a mobmov</b></font></a><br><br>
        
        <font size=4>Ready? Apply below:</font>
        <? 
		}

if (!$done) {
?>

<form action="drive.php" method="post">
<input type="hidden" name="app" value="new">
<input type="hidden" name="form" value="app.txt">
<input type="hidden" name="to" value="info@mobmov.org">
<input type="hidden" name="subject" value="mobmov: new chapter application">

<table border=0 cellpadding=4>
	<tr>
    	<td align="right" valign="top"><b>Full Name</b></td>
        <td><input type="text" name="name" size="38" value="<?=$post_name?>"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Phone</b></td>
        <td><input type="text" name="member_phone" size="38" value="<?=$post_member_phone?>"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Email</b></td>
        <td><input type="text" name="email" size="38" value="<?=$post_email?>"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Password</b></td>
        <td><input type="password" name="password" size="20" value="<?=$post_password?>"> Confirm: <input type="password" name="password_confirm" size="20" value="<?=$post_password_confirm?>"> </td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Forum username</b></td>
        <td><input type="text" name="username" size="38" value="<?=$post_username?>"><br/><i>If you already have a forum username, leave this blank and we'll look it up based on your email address.</i></td>
    </tr>
    
    <?if ($preselect_chapter) {?>
    <input type="hidden" name="city" value="NA">
    <input type="hidden" name="state" value="NA">
    <input type="hidden" name="country" value="NA">
    <input type="hidden" name="preselect_chapter" value="<?=$preselect_chapter?>">
    <? } else { ?>
    <tr>
    	<td align="right" valign="top"><b>Chapter City</b></td>
        <td><input type="text" name="city" size="38" value="<?=$post_city?>"><br>i.e. San Francisco</td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>State</b></td>
        <td><input type="text" name="state" size="4" maxlength="2" value="<?=$post_state?>"> (if in US)<br>i.e. CA</td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Country</b></td>
        <td><input type="text" name="country" size="38" value="<?=$post_country?>"> (if not US)<br>i.e. United Kingdom</td>
    </tr>
    <?
	}
    ?>
    <tr>
    	<td align="right" valign="top"><b>Your Mailing Address</b><br><font size=1>optional</font></td>
        <td><textarea name="member_address" cols="38" rows="6"><?=$post_member_address?></textarea></td>
    </tr>
    <tr>
    	<td align="right" valign="top"></td>
        <td><br><b>Why do you want to run a mobmov?</b></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b></b></td>
        <td><textarea name="whyyou" cols="38" rows="6"><?=$post_whyyou?></textarea></td>
    </tr>
<tr>
    	<td align="right" valign="top"></td>
        <td><b>To prevent spam, please type "mobmov" in the box:</b><br><input type="text" name="verify" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
        <td><input type="submit" name="Make a mob!" value="Make a mob!"></td>
    </tr>
</table>

</form>
<? 
} 
?>	

<? require_once("common/bot.php"); ?>