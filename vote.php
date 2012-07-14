<? require("common/top.php"); ?>
<?
	db_connect();

	$vote = $_GET['vote'];
	$poll = $_GET['poll'];
    $email = $_GET['u'];

if ($poll) {

// current poll data
	$SQL ="SELECT *,UNIX_TIMESTAMP(enddate) AS ending FROM showings_votes WHERE showing_poll_id='$poll' LIMIT 1";
    $result = mysql_query($SQL);
    if (mysql_num_rows($result)==0) {
    	 print "<b>That poll can't be found!</b> Please check the link and try again.<br><br>"; 
    } else {
		while($row = mysql_fetch_array($result)){ 
			foreach($row AS $key => $val){ 
				$key = stripslashes(strtolower($key));
				$$key = $val; 
			}
		}

// REGISTER VOTE  
    if ($vote && $poll) {
    
    	// can we vote?
        if ($endcount && ($total_votes + 1 > $endcount)) {
        	 print "<b>I'm sorry, but this poll closed after $endcount votes, so you vote cannot be recorded.</b> Thank you and good luck.<br><br>"; 
             $cantvote = true;
             $closed=true;
        } else if ($ending && ($ending < time())) {
        	print "<b>I'm sorry, but this poll closed on ".date("m/d/Y",$ending).", so you vote cannot be recorded.</b> Thank you and good luck.<br><br>"; 
             $cantvote = true;
             $closed=true;
        } else if (!$email) {  
          print "<b>I'm sorry, but the link you clicked on is invalid.</b> Please check the link and try again.<br><br>"; 
             $cantvote = true;
          
        } else if (strpos($canvote,$email) === false) {
        	//print "<b>I'm sorry, but either you have already voted, or you can't vote in this poll.</b> Thank you and good luck.<br><br>"; 
             $cantvote = false;
        }
        
    	$SQL ="SELECT canvote FROM showings_votes WHERE showing_poll_id='$poll'";
    	$result2 = mysql_query($SQL);
       	$canvote = @mysql_result($result2,0,0);
        if (!stristr($canvote,$email)) {
        	$cantvote = true;
           	print "<b>Hey no fair!</b> It seems that you've tried to vote again or you weren't allowed to vote in the first place. Please don't try to cheat the system!<br><br>";
        }
		//if (!isset($_COOKIE["Voted".$poll])) {
        if (!$cantvote) {
        	$canvote = str_replace($email,"",$canvote);
            $canvote = str_replace(",,","",$canvote);
            $SQL ="UPDATE showings_votes SET canvote='$canvote',total_votes=total_votes+1,choice_".$vote."_votes=choice_".$vote."_votes+1 WHERE showing_poll_id='$poll' LIMIT 1";
            $result = mysql_query($SQL);
            
            //$cc_id = mysql_insert_id();
            if ($result) { 
    			//setcookie("Voted".$poll, "y", time()+YEAR);  /* expire in 1 hour */
            	print "<b>Your vote has been recorded!</b> Thank you and good luck.<br><br>"; 
				
        	}
		} else {
			//print "<b>Hey no fair!</b> It seems that you've tried to vote again or you weren't allowed to vote in the first place. Please don't try to cheat the system!<br><br>"; 
		}
		
		$total_votes++;
    	$nameofit = "choice_".$vote."_votes";
    	$$nameofit++;
	}
    	
    $max_width = 200;
    
    if ($total_votes) {
    $choice_1_width = ($choice_1_votes / $total_votes) * $max_width+5;
    $choice_2_width = ($choice_2_votes / $total_votes) * $max_width+5;
    $choice_3_width = ($choice_3_votes / $total_votes) * $max_width+5;
    $choice_4_width = ($choice_4_votes / $total_votes) * $max_width+5;
    $choice_5_width = ($choice_5_votes / $total_votes) * $max_width +5;
    }
	
	$highest = array($choice_1_votes => "1",$choice_2_votes => "1",3 => $choice_3_votes,4 => $choice_4_votes,5 => $choice_5_votes);
	ksort($highest);
	$highest[0];
	
	$basic_color = "#660011";
	$hi_color = "#CC2222";
	$choice_1_color = $basic_color;
	if ($choice_1_votes >= $choice_2_votes && 
		$choice_1_votes >= $choice_3_votes && 
		$choice_1_votes >= $choice_4_votes && 
		$choice_1_votes >= $choice_5_votes 
		) {
		$choice_1_color = $hi_color;
	}
	$choice_2_color = $basic_color;
	if ($choice_2_votes >= $choice_1_votes && 
		$choice_2_votes >= $choice_3_votes && 
		$choice_2_votes >= $choice_4_votes && 
		$choice_2_votes >= $choice_5_votes 
		) {
		$choice_2_color = $hi_color;
	}
	$choice_3_color = $basic_color;
	if ($choice_3_votes >= $choice_1_votes && 
		$choice_3_votes >= $choice_2_votes && 
		$choice_3_votes >= $choice_4_votes && 
		$choice_3_votes >= $choice_5_votes 
		) {
		$choice_3_color = $hi_color;
	}
	$choice_4_color = $basic_color;
	if ($choice_4_votes >= $choice_1_votes && 
		$choice_4_votes >= $choice_3_votes && 
		$choice_4_votes >= $choice_2_votes && 
		$choice_4_votes >= $choice_5_votes 
		) {
		$choice_4_color = $hi_color;
	}
	$choice_5_color = $basic_color;
	if ($choice_5_votes >= $choice_1_votes && 
		$choice_5_votes >= $choice_3_votes && 
		$choice_5_votes >= $choice_4_votes && 
		$choice_5_votes >= $choice_2_votes 
		) {
		$choice_5_color = $hi_color;
	}
    ?>
	<span class="header-page"><?=$subject?></span><br><br>
    <table border=0 cellpadding=4 cellspacing=0>
    
    <tr>
    	<td align="right"><?=$choice_1?></td>
    	<td width="<?=$max_width?>"><table border=0 cellpadding=4 width="100%"><tr><td bgcolor="<?=$choice_1_color?>" width="<?=$choice_1_width?>">&nbsp;</td><td bgcolor="#eeeeee">&nbsp;</td></tr></table></td>
    </tr>
    <tr>
    	<td align="right"><?=$choice_2?></td>
    	<td width="<?=$max_width?>"><table border=0 cellpadding=4 width="100%"><tr><td bgcolor="<?=$choice_2_color?>" width="<?=$choice_2_width?>">&nbsp;</td><td bgcolor="#eeeeee">&nbsp;</td></tr></table></td>
    </tr>
    <? if ($choice_3) { ?>
    <tr>
    	<td align="right"><?=$choice_3?></td>
    	<td width="<?=$max_width?>"><table border=0 cellpadding=4 width="100%"><tr><td bgcolor="<?=$choice_3_color?>" width="<?=$choice_3_width?>">&nbsp;</td><td bgcolor="#eeeeee">&nbsp;</td></tr></table></td>
    </tr>
    <? }
    if ($choice_4) { ?>
    <tr>
    	<td align="right"><?=$choice_4?></td>
    	<td width="<?=$max_width?>"><table border=0 cellpadding=4 width="100%"><tr><td bgcolor="<?=$choice_4_color?>" width="<?=$choice_4_width?>">&nbsp;</td><td bgcolor="#eeeeee">&nbsp;</td></tr></table></td>
    </tr>
    <? }
    if ($choice_5) { ?>
    <tr>
    	<td align="right"><?=$choice_5?></td>
    	<td width="<?=$max_width?>"><table border=0 cellpadding=4 width="100%"><tr><td bgcolor="<?=$choice_5_color?>" width="<?=$choice_5_width?>">&nbsp;</td><td bgcolor="#eeeeee">&nbsp;</td></tr></table></td>
    </tr>
    <? } ?>
    <tr>
    	<td align="right"><b>Total votes:</b></td>
    	<td width="<?=$max_width?>"><b><?=$total_votes?></b>&nbsp;&nbsp;<? if ($closed) { ?>This poll is closed.<? } ?></td>
    </tr>
    </table>
    
<? 
	 }
} else {
	print "No poll selected.";
} 
?>

<? require_once("common/bot.php"); ?>