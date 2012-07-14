<?
$page="HOME";
require("common/top.php");
?>

<table border=0 cellpadding=0 cellspacing=0 >

<!-- INTRO -->
<TR>
    <TD width="700" height="250" valign="top">
            <div onclick="window.location='signup.php'" style="background: url('graphics/mobmov_welcome.jpg'); width:700px; height:248px; margin-bottom:16px; position: relative;border:1px solid #333333;">
                <div style="position:absolute; top:100px; right:50px; font-size:13pt; color:#ffffff">1. Drive up<br>2. Tune your radio<br>3. Sit back and relax<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;at the modern drive-in</div>
            </div>
    </TD>
    <td rowspan="2" valign="top" width="15"><IMG src="graphics/nada.gif" alt="mobmov" border="0" width="15" height="10"></td>
    <td rowspan="2" height="600" valign="top" style="">
   <?=roundedbox(TOP,"111111","12","240");?>
        <div style="color:white;margin-bottom:16px;padding-right:10px;">
        <span style="font-size:14pt;font-weight:bold;color:#f6d44d;">
        join the mobmov!<br/>
        </span>
        <span style="font-size:8pt">
        find out about upcoming shows:<br/><br/>

        <FORM id="MailingList" action="/signup.php?step=2" method="get" name="ML">
                    <INPUT type="hidden" name="action" value="addtolist">


                                <TABLE border="0" cellspacing="0" cellpadding="3">

                            <TR>
                                <TD align="right" width="50" style="color:white;font-size:12px;"><B>email:</B></TD>
                                <TD><INPUT type="text" name="email" style="border:1px solid;" size="22" value="<? print $email ?>" TABINDEX=1></TD>

                            </TR>

                                    <TR>
                                        <TD align="right" width="50" style="color:white;font-size:12px;"><B>zip:</B></TD>
                                        <TD><INPUT type="text" name="zip" style="border:1px solid;" size="22" value="<? print $zip ?>" TABINDEX=1></TD>

                                    </TR>


                            <TR>
                                <TD align="right" style="color:white;font-size:12px;"><B>location:</B></TD>
                                <TD>
                                    <select name="chapter" TABINDEX=3 style="border:1px solid;">
                                    <option value="">NEWS &amp; INFO ONLY</option>
                    <option value=""> </option>
                            <?

                            $preselect_chapter = $_GET['preselect_chapter'];
                            if (!$preselect_chapter) { $preselect_chapter = 195; }

                    db_connect();
                    $SQL ="SELECT chapter_id,city,state,country,new FROM chapters WHERE accepting='y' AND approved ='y' ORDER BY country DESC,state,city";
                    $result = mysql_query($SQL);
                    $last_country ="";
                    $last_state ="";
                    while($row = mysql_fetch_array($result)){
                        $new = $row['new'];
                        $location = $row['city'];
                        $country = $row['country'];
                        if (!$country) {
                            $country = "United States";
                        }
                        if ($country != $last_country) {
                            $last_country = $country;
                            $country = "-- " . $country . " ";
                            print "<option value=''>".str_pad(strtoupper($country),25,"-")."</option>";
                        }

                        if ($row['state']) {
                            $location .= ", " . $row['state'];
                        } else {
                            //$location .= ", " . $row['country'];
                        }
                        $chapter_id = $row['chapter_id'];
                        ?>
                            <option value="<?=$chapter_id?>" <? if ($preselect_chapter == $chapter_id) {?>selected<? } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$location?> <? if ($new == 'y') {?><? } ?></option>



                  <? } ?>


                            </select></td></tr>

                          <!-- <TR>
                                <TD align="right" width="50" style="color:white;font-size:12px;"><B>zip:</B></TD>
                                <TD><INPUT type="text" name="zip" style="border:1px solid;" size="10" value="<? print $zip ?>" TABINDEX=1></TD>

                            </TR>-->



                            <tr><td></td><td>
                            <INPUT type="image" src="graphics/joinusb.gif" name="join" value="join us" TABINDEX=4 onmouseover="this.src='graphics/joinusb_over.gif'" onmouseout="this.src='graphics/joinusb.gif'"> <span class="small"></span></td></tr>


                        </TABLE>


                        </FORM>


        </span>
        </div>
        <?=roundedbox(BOTTOM);?>

        <br>
        <?=roundedbox(TOP,"111111","12","240");?>
        <div align="center" class="hand" onclick="window.location='film.php'">

                <?=roundedbox(TOP,"d7d7d7","8","200");?>
                       <span style="font-size:8pt">
                       <b>Filmmakers:</b> gain a worldwide audience by releasing your film through the MobMov. <a href="film.php"  style="color:black"  class="nodeco"><u>Find out more</u></a>

                <?=roundedbox(BOTTOM);?>
       </div>
        <?=roundedbox(BOTTOM);?>
                   <br>
            <?=roundedbox(TOP,"111111","12","240");?>
                   <div align="center"  class="hand" onclick="window.location='news.php'">
                   <?=roundedbox(TOP,"ffffff","8","200");?>

                        <div align="center">
                       <a href="news.php" class="nodeco"><img src="graphics/mobmov_press.gif" width="200" height="200" border=0/></a>
                        </div>

                    <?=roundedbox(BOTTOM);?>
        </div>
        <?=roundedbox(BOTTOM);?>
        <br>


    </td>
</TR>

<!-- BUTTONS -->
<TR>
    <TD height="600" valign="top">
        <table border=0 cellpadding=8 cellspacing=0 id="header-mid">
            <tr>
                <td valign="top">
                <span class="header-font" style="font-size:15pt;line-height:2em">This is the modern drive-in:</span><br/>
                <span>The <b><a href="http://en.wikipedia.org/wiki/Mobmov" target="_blank" style="text-decoration:underline">Mobile Movie</a></b> is bringing back the forgotten joy of <a href="http://en.wikipedia.org/wiki/Drive-in_theater" target="_blank" style="text-decoration:underline">the great American drive-in</a> in urban settings across the world. <b>Powered by cars</b> and video projectors, MobMov's are easy and affordable to set up. Abandoned warehouse walls spring to life with the sights and sounds of a big screen movie.
                 <a href="/about"  class="nodeco" style="text-decoration:underline">read more</a></span>

                <br/><br/>MobMov.org has been in operation since 2005, and is the earliest "urban drive-in" of it's kind. In fact, the term "MobMov", short for Mobile Movie, was originally coined by <a href="http://aboutbryan.com">Bryan Kennedy</a>, the founder of mobmov.org, to describe the unique "drive-in that drives-in" method he developed.

                <br/><br/>Today, MobMov.org is powered by do-it-yourselfers like you across the globe, all joined together in the goal to bring drive-ins back in a new and sustainable way. It's free to join and lots of fun.


                </td>
                <td width="330" valign="top" class="hand" rowspan="2">
                    <?=roundedbox(TOP,"111111","12","400");?>
                    <div style="color:white;margin-bottom:16px;padding-right:10px;">
                        <object width="400" height="350">
                        <param name="movie" value="http://www.youtube.com/v/0ANPNpETtpk&autoplay=0"> </param>
                        <param name="bgcolor" value="#000000"> </param>
                        <param name="wmode" value="transparent"> </param>
                        <embed src="http://www.youtube.com/v/0ANPNpETtpk&&autoplay=0" type="application/x-shockwave-flash" wmode="transparent" width="400" height="350"></embed>
                        </object>
                    </div>
                    <?=roundedbox(BOTTOM);?>

               </td>
            </tr>


          </table>

    </td>
</tr>
</table>
 
<? require("common/bot.php");?>
	
