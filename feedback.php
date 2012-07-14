<? require("common/top.php"); ?>

<span class="header-page">Feedback</span><br>

<? 
if ($_GET['success']) {
?>
<b>Thanks for your message!</b> If you entered your e-mail, we'll respond soon. Promise!
<? } elseif ($_GET['error']=='blank') { ?>
<font color='red'><b>Whoops, you may have left something important blank.</b></font><br>
<?}
?><p>Please tell us how we're doing! Your comments help us improve.
<form action="email.php" method="post">
<input type="hidden" name="form" value="contact.txt">
<input type="hidden" name="to" value="info@mobmov.org">
<table border=0 cellpadding=4>
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
    	<td align="right" valign="top"></td>
        <td><br><b>Feedback</b></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b></b></td>
        <td><textarea name="saywhat" cols="38" rows="6"></textarea></td>
    </tr>
<tr>
    	<td align="right" valign="top"></td>
        <td><b>To prevent spam, please type "mobmov" in the box:</b><br><input type="text" name="verify" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
        <td><input type="submit" name="Send" value="Send"></td>
    </tr>
</table>

</form>



<? require_once("common/bot.php"); ?>