<? require("common/top.php"); ?>

<IMG src="graphics/mediainfo.jpg" width="400" height="300" align="right" border=2 hspace="8">
<span class="header-page">media info</span><br>


    <br>The mobmov has been featured in everything from blogs to the BBC: <br><a href="news.php" style="color:blue"><b><h2><u>Coverage</u></h2></b></a>
    <a href="http://flickr.com/search/?q=mobmov&s=int" style="color:blue"><b><h2><u>Photos</u></h2></b></a>
    <p>	
	 Please contact us by e-mail below and we'll answer all your questions quickly so you can meet your deadline. Thank you for your interest.<br><br></P>
      
   <? 
if ($_GET['success']) {
?>
<font color='red'><b>Thanks for your message!</b> We'll get back to you right away.</font><br>
<? } elseif ($_GET['error']=='blank') { ?>
<font color='red'><b>Whoops, you may have left something important blank.</b></font><br>
<?}
?>   
        <form action="email.php" method="post">
<input type="hidden" name="form" value="press.txt">
<input type="hidden" name="to" value="info@mobmov.org">
<input type="hidden" name="subject" value="mobmov: press">
<table border=0 cellpadding=4>
	<tr>
    	<td align="right" valign="top"><b>Name:</b></td>
        <td><input type="text" name="name" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Organization:</b></td>
        <td><input type="text" name="company" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Email:</b></td>
        <td><input type="text" name="email" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Phone:</b></td>
        <td><input type="text" name="phone" size="38"></td>
    </tr>
    <tr>
    	<td align="right" valign="top"><b>Message:</b></td>
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