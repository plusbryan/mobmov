<? require("common/top.php"); ?>

<span class="header-page">Apply for a MobMov Theatrical Release</span><br>
Are you the producer or director of an <b>independant film or short</b>? The MobMov offers you a unique chance at <b>worldwide promotion and distribution</b>, through our network of free mobile cinemas. Use this form to apply to have your film <b>"Released by MobMov"</b>.
<br/><br/>
<b>We heart:</b>
<ul>
 <li>Family-friendly films. <br>Because we screen out in the open, we generally limit language and nudity to PG-13.<br><br></li>
 <li>Any film genres. But comedy and light-hearted films see the widest distribution.<br>
      Pro-action (environmental, political) are popular as well.<br><br></li>
 <li>You must own any and all copyrights to the work you submit here. The mobmov supports creative people and the films they create.</li>

</ul>

<b>A mobmov release is a good choice, because:</b>
<ul>
 <li>Your film will be shown for free in communities across the world -- no more $16 movie tickets!</li>
 <li>It's more a community experience than either the cineplex or online distribution alternatives</li>
 <li>We promote and distribute your film at no cost to you</li>
 <li>You can make money by attaching trailers to your films</li>
 <li>We stand against the stifling world of film distribution, opening doors for independent filmmakers and creative, ground-breaking films</li>
</ul>
<br/>
<? 
if ($_GET['success']) {
?>
<font color='red'><b>Thanks for applying!</b> We'll get back to you soon.</font><br>
<? } elseif ($_GET['error']=='blank') { ?>
<font color='red'><b>Whoops, you may have left something important blank. See below:</b></font><br>
<?}
?>  
<form action="email.php" method="post">
<input type="hidden" name="form" value="film.txt">
<input type="hidden" name="subject" value="Application for MM Film Distribution">
<? 
 	$emailto = "info@mobmov.org";
  ?>
<input type="hidden" name="to" value="<?=$emailto?>">
<table border=0 cellspacing=1 cellpadding=5>
<tr  bgcolor="#ddd">
    	<td align="right" valign="top" width="80"><b>Film Title</b></td>
        <td><input type="text" name="title" size="38"></td>
    </tr>
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Film Genre</b></td>
        <td><input type="text" name="genre" size="38"></td>
    </tr>
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Short Synopsis</b></td>
        <td><textarea name="desc" cols="38" rows="4"></textarea></td>
    </tr>
    <tr bgcolor="#ddd"><td></td>
        <td>Is your film or a trailer available online? If so, what is the url:<br/><input type="text" name="url" size="38"></td>
    </tr>
    <tr bgcolor="#ddd"><td></td>
    	<td valign="top">Would you be interested in attending a mobmov premiere<br/> of your show in a major city? If so, which city:<br/>
    	<input type="text" name="premiere" size="38">
    	</td>
        
    </tr>
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b></b></td>
        <td>Has your film been distributed before? If so, how and where?<br/>
        <textarea name="history" cols="38" rows="4"></textarea><br><br></td>
    </tr>
    

	<tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Your Name</b></td>
        <td><input type="text" name="name" size="38"></td>
    </tr><!-- 
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Production Roll</b></td>
        <td><input type="text" name="roll" size="38"> (i.e. producer, director, etc)</td>
    </tr> -->
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Email</b></td>
        <td><input type="text" name="email" size="38"></td>
    </tr>
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b>Phone</b></td>
        <td><input type="text" name="phone" size="38"> (optional)</td>
    </tr>
    
     <tr bgcolor="#ddd">
    	<td align="right" valign="top"></td>
        <td><br><b>Is there anything else we should know?</b> <br/>(licensing terms, availability, etc)</td>
    </tr>
    <tr bgcolor="#ddd">
    	<td align="right" valign="top"><b></b></td>
        <td><textarea name="other" cols="38" rows="3"></textarea></td>
    </tr>
	<tr bgcolor="#ddd">
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