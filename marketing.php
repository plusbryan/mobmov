<? require("common/top.php"); ?>

<IMG src="/graphics/marketing.jpg" width="400" height="267" align="right" border=2 hspace="8">


<span class="header-page">Put the power of the Mobile Movie<br/>behind your brand</span><br><br>

<? 
if ($_GET['success']) {
?>
<font color='red'><b>Thanks for your message!</b> We'll be with you shortly.<br><br></font>
<? } elseif ($_GET['error']=='blank') { ?>
<font color='red'><b>Whoops, you may have left something important blank.</b></font><br><br>
<?}
?>
 
Want to connect with your target audience in a <b>authentic</b> and <b>attention-grabbing</b> way that &quot;sticks&quot;? Whether it's a trailer for an upcoming movie or a new, cutting-edge product, our objective is to make the process easy for you and create <b>memorable</b> experiences for your target audience. 
<p>
Our services have been featured on TV, on the radio, in the New York Times, <a href="/media/Time_Aug06.pdf">Time Magazine</a>, the <a href="BBC_Oct05.pdf">BBC</a>, Toyota's Yaris promotional brochure and everything in between. Now is a great time to get involved in this exciting, worldwide movement.
<p>
<h2>Services Include:</h2>
<ul>
    <li>Sponsorship Development
    <li>Trailer Pre-Roll (starting at only $250)
    <li>On-site Promotional Distribution and Giveaways
    <li>Logistics Planning / Event Activation
    <li>After-action Evaluation
</ul><p>
<h2>Why Promote With the Mobile Movie?</h2>
<p>
<b>Do Something Different!</b>
<p>
The Mob Mov drive-in system offers a unique fusion of traditional and guerilla experiences that connect with diverse demographics ranging from youth to young adults and even those hip baby boomers captivated by the nostalgia of the drive-in! And most importantly is the connectivity to the influential Mob Mob community - tens of thousands strong who play a role in the selection and implementation process.
<p>
<b>Distribution Of Any Reach</b>
<p>
Whether it's a 30-second trailer shown in a few select markets, or a 5-minute short shown around the world, we're here to get the word out for you.
<p>
<b>Let Mob Mov Do The Work</b>
<p>
Our team will take care of the logistics while you focus on your core business. From set-up, local and national staffing, to distribution we have you covered.
<p>
<b>We Win If You Win!</b>
<p>
Our goal is to meet or exceed your goals. We only win if you are successful - period. Please contact us today so we may learn more about your project and provide you a price quote for MobMov services in your target markets.
<p>

        <form action="email.php" method="post">
<input type="hidden" name="form" value="contact.txt">
<input type="hidden" name="to" value="info@mobmov.org">
<input type="hidden" name="subject" value="mobmov: marketing">
<table border=0 cellpadding=4>
	<tr>
    	<td align="right" valign="top"><b>Your Name:</b></td>
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