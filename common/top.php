<?
require_once("functions.php");

// handle mailing list actions
$action = request('action',GET);
$removefromlist = request('rem',GET);

if ($action == "addtolist" || $action == "more") {
    // ADD TO MAILING LIST
    // POST: email, name, chapter (multiple ids)
    db_connect();

    $post_member_id = request('member_id',GET);

    if (!$post_member_id) {
        $email = filterformat(EMAIL,request('email',GET));
        $name = capitalize(addslashes(request('name',GET)));
        $movie = capitalize(addslashes(request('movie',GET)));
        $zip = request('zip',GET);
        $sms = request('sms',GET);
        $zipradius = request('zipradius',GET);
    }

    if ($_GET['chapter']) {
        $signup_chapters = split(",",$_GET['chapter']);
    } else {
        $signup_chapters = $_GET['chapters'];
    }

    if (!$email && !$post_member_id)
    {
        $status_message .= "<font color='#990000'><b>Please enter a valid e-mail address.</b></font> We're not going to do anything nasty to it. Really.<br>";
        $action = "step1";
    }
    else
    {
        // *** ARE THEY ALREADY A MEMBER? ************************
        $member_id=0;
        if (!$post_member_id) {
            $SQL ="SELECT member_id FROM mailinglist WHERE email='%s' LIMIT 1";
            $result = mysql_query_safe($SQL,$email);
            if (mysql_num_rows($result) > 0) {
                $member_id = mysql_result($result,0,0);
            }
            //if ($member_id) { $exists = true; }
            //$num_results = mysql_num_rows($result);
        } else {
            $member_id = $post_member_id;
            //$exists = true;
        }

        // *** MAKE THEM AN ACCOUNT IF NOT A MEMBER ALREADY *******
        if (!$member_id)
        {
            $SQL ="INSERT INTO mailinglist SET sms='%s',name='%s',zip='%s',zipradius='%s',movie='%s',email='%s',ip_address='%s',membersince=NOW()";
            $resultadd = mysql_query_safe($SQL,$sms,$name,$zip,$zipradius,$movie,$email,$_SERVER["REMOTE_ADDR"]);
            $member_id = mysql_insert_id();

            // send them a confirmation email

            if ($email)
            {
                $hidemailinglist = true;
                // thank them for joining by e-mail
                $to = $email;
                $fromname = "MobMov";
                $from = "info@mobmov.org";
                $subject = "Please confirm your email";
                $body = "A very warm welcome to you! Before you can get notifications of drive-in events in your area, you'll need to confirm your email address with us by clicking this link:\n\n
http://mobmov.org/signup?action=confirm&mem=$member_id
\r\nRemember join our twitter feed (http://twitter.com/mobmov) for news and events. We'll be looking forward to meeting you at our next event!\r\n\r\nTo unsubscribe, just click:\r\n\r\nhttp://mobmov.org/?rem=$email";
                email($to,$subject,$body);
            }


        }
        $result=false;

        if (!$signup_chapters) {  /// THEY SIGNED UP THE NEWS LIST *ONLY*
            if ($resultadd)
            {
                $status_message .= "<b>You've been added to our general news list.</b> Thanks!<br>";

                $hidemailinglist = true;

            } else {
                $status_message .= "<b>You're already a member of the mob, it seems.</b> Did you make a mistake?<br>";
            }
        } else {
            // sign up for chapter lists

            // what are you signed up for, my little friend?
            $current_chapters=0;
            $SQL ="SELECT chapter_id FROM mailinglist_chapters WHERE member_id='%s'";
            $result = mysql_query_safe($SQL,$member_id);
            $chapter_ids = array();
            //$chapter_ids="";
            while($row = mysql_fetch_array($result)){
                $current_chapters++;
                $chapter_ids[] = $row['chapter_id'];
            }

            $signup_count=count($signup_chapters);
            $sent_mail=false;
            foreach ($signup_chapters as $chapter_id) {
                $chapter_name = get_chapter_name($chapter_id);

                // are you already on this chapter?
                if (!$exists = in_array($chapter_id,$chapter_ids))
                {
                    $SQL ="INSERT INTO mailinglist_chapters SET member_id='%s',chapter_id='%s'";
                    $result_insert = mysql_query_safe($SQL,$member_id,$chapter_id);
                    if ($result_insert) { $status_message .= "<b>You've been added to the glorious event list of ".$chapter_name.".</b> Thank you!<br>"; }
                } else {
                    // want to keep you on the list, so do nothing!
                    //$status_message .= "You're already in our events list for ".get_chapter_name($chapter_id).". But feel free to add yourself to another chapter!";
                }
                // remove from our list so we can get a remainder
                $key = array_search($chapter_id,$chapter_ids);
                unset($chapter_ids[$key]);

            }
            // if we have any remaining chapters, remove them from the list
            if ($chapter_ids && $signup_count > 1) {
                foreach ($chapter_ids as $chapter_id) {
                    if ($chapter_id) {
                        $chapter_name = get_chapter_name($chapter_id);
                        $SQL ="DELETE FROM mailinglist_chapters WHERE member_id='%s' AND chapter_id='%s'";
                        mysql_query_safe($SQL,$member_id,$chapter_id);
                        $status_message .= "You have been removed from the $chapter_name chapter mailing list.<br/>";
                    }
                }
            }
        }



        //}
    }


} else if ($action == "others") {
    db_connect();
    //$others = addslashes($_GET['others1']) . "," . addslashes($_GET['others2']) . "," . addslashes($_GET['others3']);
    $others = $_GET['others'];
    if ($others)
    {
        $others = split($others,",");
        foreach ($others as $other) {
            $other = filterformat(EMAIL,$other);
            if ($other) {
                $to = $other;
                $fromname = $name;
                $from = $email;
                $subject = "let's go to the drive-in!";
                $body = "http://mobmov.org"; //\r\n\r\n--\r\nThis message was sent using the form on the mobmov.org web site by someone who thought you'd be interested. Please rest assured that you have not been added to any mailing list.";
                email($to,$subject,$body);
            }
        }
        if ($to) {$status_message .= "<b>Thanks, your friends have been e-mailed</b>. Be sure to invite them to the next show too!<br>";}
        $action == "setprefs";
    }
} else if ($action == "setprefs") {
    db_connect();
    $member_id = $_GET['member_id'];
    $age = $_GET['age'];
    $gender = $_GET['gender'];
    $movie = $_GET['movie'];
    $mail_adverts = $_GET['mail_adverts'];
    if ($member_id)
    {
        $SQL ="UPDATE mailinglist SET ";
        if ($age) {
            $SQL .= "age = '$age' ,";
        }
        if ($gender) {
            $SQL .= "gender = '$gender' ,";
        }
        if ($movie) {
            $SQL .= "movie = '$movie' ,";
        }
        if ($mail_adverts == "y") {
            $SQL .= "mail_adverts = 'y' ,";
        }
        if ($mail_announce == "y") {
            $SQL .= "mail_announce = 'y' ,";
        }
        if ($mail_events == "y") {
            $SQL .= "mail_events = 'y' ,";
        }
        $SQL = substr($SQL,0,-1);
        $SQL .= " WHERE member_id = '$member_id'";
        //print $SQL;
        $result = mysql_query($SQL);
        if ($result) { $status_message .= "Thanks for helping make the mobmov better!<br>"; }
    } else {
        $status_message .= "Error: No membership number associated with this account.<br>";
    }

} else if ($removefromlist) {
    // REMOVE FROM LIST
    //
    db_connect();
    $removefromlist_cha = $_GET['cha'];
    $removefromlist_id = $_GET['rem_id'];
    if ($removefromlist_cha && $removefromlist) {
        $member_id = get_member_id($removefromlist);
        $chapter_name = get_chapter_name($removefromlist_cha);
        if ($member_id && $chapter_name) {
            $SQL ="DELETE FROM mailinglist_chapters WHERE chapter_id='%s' AND member_id='%s' LIMIT 1";
            $result = mysql_query_safe($SQL,$removefromlist_cha,$member_id);
            if (get_member_id($removefromlist)) {
                $stillon = "You're still on other mobmov chapter/news lists. To remove yourself from all lists, click <a href='http://mobmov.org/?remv=$removefromlist'>here</a>.";
            }
            if ($result) { $status_message .= "You have been removed from the mailing list for ".$chapter_name.".<br>$stillon"; }
        } else {
            $status_message .= "You aren't on any more lists that I can see.";
        }
        //print $SQL;


    } else if ($removefromlist) {
        $member_id = get_member_id($removefromlist);
        $email = addslashes($removefromlist);
        $SQL ="DELETE FROM mailinglist WHERE email='%s' LIMIT 1";
        $result = mysql_query_safe($SQL,$email);
        if ($result) { $status_message .= "You have been removed from ALL mailing lists. Sorry to see you go, really!<br>"; }

        $SQL ="DELETE FROM mailinglist_chapters WHERE member_id='%s' LIMIT 1";
        $result = mysql_query_safe($SQL,$member_id);
    }
}

$rsvp = $_GET['rsvp'];

if ($rsvp) {
    $email = $_GET['email'];
    $num = $_GET['num'];
    $old_num = $_GET['old_num'];
    $rem_email = $_GET['rem_email'];

    if (!$num) {
        $num = 1;
    }
    if ($old_num) {
        $num = $num - $old_num;
    }
    if ($email) {
        $email_sql = ",rsvp_list=REPLACE(rsvp_list,',$rem_email',''),rsvp_list=CONCAT(rsvp_list,',','$email')";
        $message = '<b><font color="#cc0000">Thank you, your RSVP is confirmed.</font></b> See you at the show! <a href="?rsvp='.$rsvp.'&rem_email='.$email.'&num=0&old_num='.$num.'">Need to remove your rsvp?</a>';
    } elseif ($rem_email) {
        $email_sql = ",rsvp_list=REPLACE(rsvp_list,',$rem_email','')";
        $message = '<b><font color="#cc0000">Your RSVP has been removed.</font></b> See you at the next how we hope!';
    }
    $SQL = "UPDATE showings SET rsvps=rsvps+%s $email_sql WHERE showing_id = '%s'";
    $result = mysql_query_safe($SQL,$num,$rsvp);
    $num =
        /*
      if ($num == 1) {
          $num_output = "just you";
      } elseif ($num == 0) {
          $num_output = "not coming";
      } else {
          $num_output = "me + ".($num - 1)." friends";
      }
      */
    $status_message .= ''.$message.'<br><br>
<form action="?" method="GET">
<input type="hidden" name="old_num" value="'.$num.'"><input type="hidden" name="rsvp" value="'.$rsvp.'"><input type="hidden" name="email" value="'.$rem_email.'">
<b>Bringing more people?</b> In your group: <select name="num"><option>'.max($num,0).'</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option></select>&nbsp;
<input type="submit" name="Change" value="Change">&nbsp;(inc yourself)
</form>
	';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
    <META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <TITLE>MobMov: modern drive-in and Mobile Movie outdoor cinema</TITLE>
    <LINK href="http://<?=$_SERVER['SERVER_NAME']?>/common/style.css" type="text/css" rel="stylesheet" media="all">
    <meta http-equiv="imagetoolbar" content="false" />
    <META NAME="DESCRIPTION" CONTENT="The mobmov or mobile movie, is a worldwide guerilla movie movement bringing back the drive-in in a whole new way. Shows are announced online and everyone shows up at the disclosed location in their cars to watch a drive-in movie like the days of old.">
    <META NAME="KEYWORDS" CONTENT="guerilla drive-in,mobmov,moviemob,drive-in,mobile movie,drivein,car,independant film,flashmob">
</HEAD>
<?
if ($mailing_list_count) {
	$top_box = '
	<div style="border:1px solid #e39734; border-bottom:0px; width:240px;color:black; background-color: #f9e289;  padding:8px; font-size:10px;" class="hand" onclick="document.location.href=\'/venues.php\'">
	<a href="/venues.php" style="color:black;"><img src="/graphics/mobmap_s.gif" border=0 width=75 height=54 align="right"  style="margin-left:4px;" valign="middle"/></a>
	<b>the mobmov is driven by</b><br/>
    '.number_format($mailing_list_count).' members in '.$chapters_count.' mobs from '.$countries_count.' nations across the globe. 
	<a href="/venues.php" style="color:black;"><nobr>world map</nobr></a>
	</div>';
} else {
	$top_box = '
	<div style="width:240px;color:black; background-color: #f9e289; padding:8px; font-size:10px;" class="hand" onclick="document.location.href=\'/venues.php\'">
	<a href="/venues.php" style="color:black;"><img src="/graphics/mobmap_s.gif" border=0 width=75 height=54 align="right" style="margin-left:4px;"	valign="middle"/></a>
	<b>the mobmov is driven by</b><br/>
    Thousands of members in mobs across the globe.
	<a href="/venues.php" style="color:black;"><nobr>world map</nobr></a>
	</div>';
	
}
?>
<? if ($body_tag<>'') { print $body_tag; } else { print '<BODY>'; } ?>
<TABLE width="100%"  border="0" cellpadding="0" cellspacing="0">
	<TR>
		<TD align="center" class="header-box">
			<table border=0 cellpadding=0 cellspacing=0 width="1010">
				<tr>
					<td class="header-box" valign="bottom" width="685"><br/><br/><A href="http://<?=$_SERVER['SERVER_NAME']?>" class="nodeco"><IMG src="http://<?=$_SERVER['SERVER_NAME']?>/graphics/mobmov.gif" alt="mobile movie: the drive-in that drives in" width="685" height="67" border="0"></A></TD>
					<td class="header-box"  style="padding-left:20px;" valign="bottom" align="right"><?=$top_box?></td><td></td>
				</tr>
			</table>
		</td>
    </TR>
    
    <TR>
    	<TD colspan=2 height="5" valign="top" style="background-color:#000000;">
       </TD>
    </TR>	
    
    <TR>
    	<TD colspan=2 height="20" align="center" valign="top" style="background-color:#fff9dc; border-top: 4px solid #F6D44D;border-bottom: 4px solid #F6D44D;color:black; padding: 2px 26px 2px 36px;margin:0;">
    	<? include("links.php");?>
       </TD>
    </TR>
    
    <? if ($status_message) { ?>
    <TR>
    	<TD colspan=2 align="center" valign="middle">
    	
    		<div style="padding:15px 15px 0 15px;width:1000px;">
			<?=roundedbox(START,"ffeeaf","12","100%");?>
    		<? print $status_message ?>
    		<?=roundedbox(BOTTOM);?>
    		</div>
    		
    	</TD>
        
    </TR>    
    <? } ?>
    
    <tr>
    	<td colspan="2">
    		
            	<table border=0 cellpadding=0 cellspacing=0 width="985" height="1000" align="center">
                	<tr>
                    	<td valign="top" class="body_sub" style="background-color:#ffffff;padding:12px;border:solid #444 4px;border-top:0px;border-bottom:0px;">

