<? require("common/top.php"); ?>

<span class="header-page">Help us out</span><br>
		The mobmov is a community effort. Help us make it better?
		<ol>
				
				<li><strong>Photographers</strong><br>If you can take a good night shot, how about arranging to come to the next showing and take some photos for us to post on this site?</li><br>
                
                <li><strong>Cohorts</strong><br>We can always use good people to help choose movies, set up, or find venues.</li><br>

				<li><strong>Venues</strong><br>Do you have <a href="http://www.geocities.com/eel_411/sli101/">SLI</a>? Or at least have access to the light switch for a big wall somewhere in the bay area?</li><br>
				
				<li><strong>New Mobmovs</strong><br><a href="drive.php">Start your own mobmov</a>?</li>
                <br><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <li><strong>Donate</strong><br>Believe it or not, but we like it when you 
					
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="donations@mobmov.org">
<input type="hidden" name="item_name" value="Mobmov friend (thank you for your support!)">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="return" value="http://www.mobmov.org/thanks.php">
<input type="hidden" name="cancel_return" value="http://www.mobmov.org">
<input type="hidden" name="cn" value="Should we list you as a donor?">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" align="absbottom" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">

, and some nice people actually do sometimes send a few bucks our way. It makes everyone feel all warm and fuzzy inside, and helps keep this thing going.</li><br>
</form>
</ol>	<br>

<span class="header-page">Innovation</span><br>
It took a lot of inventive problem-solving to create the first mobmov, but there are still some technical details that, if solved, would make the experience even better!
<ol>
				
				<li><strong>12v Popcorn popper</strong><br>A movie without popcorn is like Star Wars without Han Solo. Sure, you can nuke a bag before you go, but then you're just eating cold popcorn. What we need, kids, is some way to make popcorn on the go! Can you do it in under 100w?</li><br>

				<li><strong>Light control</strong><br>Streetlamps don't have switches. Is there some easy non-dangerous, non-vandalous way we can actively (and temporarily) control streetlamp light output? Some sort of blanket on a rope might work, or perhaps a laser pointed at the light sensor? Heck, isn't there some non-nuclear way we can stop the flow of electricity in wires? I think I saw that in a movie once. Send us your not-so-bright ideas. </li><br>

</ol>	<br>

<? 
if ($_GET['success']) {
?>
<b>Thanks for your message! If you entered your e-mail, we'll respond soon. Promise!</b>
<?
} else {
?>
<span class="header-page">Talk aloud</span><br>
Join us? New idea? Tell us what's on your mind!<br><br>

<form action="email.php" method="post">
<input type="hidden" name="form" value="helpout.txt">
<input type="hidden" name="to" value="info@mobmov.org">
<input type="hidden" name="subject" value="mobmov: new offer of help">
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
    
        <td colspan=2><br><b>What do you have to say for yourself?</b></td>
    </tr>
    <tr>
    	
        <td colspan=2><textarea name="saywhat" cols="38" rows="6"></textarea></td>
    </tr>
    <tr>
    	
        <td colspan=2><input type="submit" name="Send" value="Send"></td>
    </tr>
</table>

</form>
<? 
} 
?>

<? require_once("common/bot.php"); ?>