<? require("common/top.php"); ?>

<span class="header-page">Contact us</span><br>
   <? if ($_GET['success']) {
?>
<font color='red'><b>Thanks for your message!</b> We'll get back to you right away.</font><br>
<? } elseif ($_GET['error']=='blank') { ?>
<font color='red'><b>Whoops, you may have left something important blank.</b></font><br>
<?}
?>  
<form action="email.php" method="post">
<input type="hidden" name="form" value="gencontact.txt">
<? if ($_GET['driver']) { 
		$driver = $_GET['driver'];
       db_connect();
        $SQL ="SELECT email,name FROM members WHERE member_id='$driver'";
    	$result = mysql_query($SQL);
        $emailto = mysql_result($result,0,0).",info@mobmov.org";
        $emailname = mysql_result($result,0,1);
        
 } else {
 	$emailto = "info@mobmov.org";
 } ?>
<input type="hidden" name="to" value="<?=$emailto?>">
<table border=0 cellpadding=4>
<? if ($emailname) { ?>
	<tr>
    	<td align="right" valign="top"><b>To</b></td>
        <td><?=$emailname?></td>
    </tr>
    <? } ?>
	<tr>
    	<td align="right" valign="top"><b>Your Name</b></td>
        <td><input type="text" name="name" size="38"> (optional)</td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Your Email</b></td>
        <td><input type="text" name="email" size="38"> (optional)</td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Subject</b></td>
        <td><input type="text" name="subject" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Message</b></td>
        <td><textarea name="saywhat" cols="38" rows="6"></textarea></td>
    </tr>
<tr>
    	<td align="right" valign="top"></td>
        <td><b>To help prevent spam, please type "mobmov" in the box:</b><br><input type="text" name="verify" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
        <td><input type="submit" name="Send" value="Send"></td>
    </tr>
</table>

</form>


<? require_once("common/bot.php"); ?>