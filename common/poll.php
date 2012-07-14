<?php
require_once ("functions.php");
db_connect();


// SHOW ALL POLLS
if ($show=="all") {
	//require_once ("common/top.php");
	//print "<ul class=\"poll_all\">";
	$sql=mysql_query("SELECT id,question,expires from polls ORDER BY expires,id DESC"); //(CURDATE() < expires)
	while ($data = mysql_fetch_array($sql)) {
			$name = stripslashes($data['id']);
			$expires = strtotime($data['expires']);
		  	$question = stripslashes($data['question']);
			if ($expires) {
				$EXPIRED = (time() > $expires);
			}
		  	print "<span class=\"font_poll\">&#149;&nbsp;$question? <B><a href=\"default.php?poll=$name\">";
			if ($EXPIRED) {
				print "Results";
			} else {
				print "Vote";
			}
			print "</a></b>";
						
															
			print "<br></span>";
	  }
	  //print "</ul>";
} else {
	
	// GRAB POLL DATA
	if ($poll) {
		$sql=mysql_query("SELECT * from polls WHERE id=$poll");
	} else {
		$sql=mysql_query("SELECT * from polls WHERE (CURDATE() <= expires) AND (CURDATE() >= starts) ORDER BY RAND() LIMIT 1");
	}
	$data=mysql_fetch_array($sql);
	$expires = strtotime($data['expires']);
	$POLL_NAME = $data['id'];
	$QUESTION = stripslashes($data['question']);
	$ANSWER = explode("\n",stripslashes($data['answer']));
	$REVOTE_TIME = $data['revote'];
	
	if ($expires) {
		$EXPIRED = (time() > $expires);
	}
	$RESULT_FILE_NAME = "./common/polls/".$POLL_NAME.".txt";
 	$ALREADY_VOTED = ($_COOKIE["Voted_$POLL_NAME"] == "Yes");
	
	
	
	// SHOW VOTING SCREEN
	if ($data && !$vote && !$result && !$ALREADY_VOTED && !$EXPIRED) {
		//require_once ("common/top.php");
		print "<FORM METHOD=\"POST\"><input type=\"hidden\" name=\"poll\" value=\"$POLL_NAME\">"
				."<TABLE WIDTH=\"100%\" BORDER=0 cellpadding=1>"
				."<TR><TD><span class=\"font_poll\"><STRONG>$QUESTION?</STRONG></span></TD></TR>";
		while (list($key, $val) = each($ANSWER)) {
			print "<TR><TD><span class=\"font_poll\"><INPUT TYPE=\"radio\" NAME=\"answer\" VALUE=\"$key\" border=0 class=\"input_radio\"> $val</span></TD></TR>";
		} 
		print "<TR><TD><span class=\"font_poll\"><INPUT TYPE=\"Submit\" NAME=\"vote\" VALUE=\"Vote\"> <!--<a href=\"default.php?show=all\">View Other Polls</a>--></span></TD></TR>";
		// echo "<TR><TD align=\"center\"><INPUT TYPE=\"Submit\" NAME=\"result\" VALUE=\" See Result \"></TD></TR>\n";
		print "</TABLE></FORM>";
	} else {
		if (!file_exists($RESULT_FILE_NAME)) {
			$fp = fopen("$RESULT_FILE_NAME", "w");
			fclose($fp);
		}
		$file_array = file($RESULT_FILE_NAME);
	
		// SAVE VOTE
		if (!$ALREADY_VOTED && !$EXPIRED) {
			if ($answer < count($ANSWER) && $vote) {
				if (count($file_array) < count($ANSWER))  {
					$file_array = array("0\n", "0\n", "0\n", "0\n", "0\n", "0\n", "0\n", "0\n", "0\n", "0\n");
				}
				$old_answer = $file_array[$answer];
				$old_answer = preg_replace("/\n\r*/", "", $old_answer);
				$file_array[$answer] = ($old_answer + 1)."\n";
		
				$file = join('', $file_array);
				$fp = fopen("$RESULT_FILE_NAME", "w"); //or error("Can not write \$RESULT_FILE_NAME");
				flock($fp, 1);
				fputs($fp, $file);                                                     
				flock($fp, 3);
				fclose($fp);
				if ($REVOTE_TIME > 0) {
					setcookie ("Voted_$POLL_NAME","Yes",time()+$REVOTE_TIME);
				}
			}
		}
		
		// require_once ("common/top.php");
		//$file_array = file($RESULT_FILE_NAME) or print "";
		if ($data) {	
			// DISPLAY RESULT
			while (list($key, $val) = each($file_array)) {
				$total += $val;
			}
		
			// echo "<h2>Poll Results:</h2>";
			print "<TABLE CELLPADDING=1 BORDER=0>";
			print "<TR><TD colspan=\"2\"><span class=\"font_poll\"><STRONG>$QUESTION?</STRONG></span></TD></TR>\n";
			//echo "<tr><th>What</th><th>Percentage</th><th>Votes</th></tr>";
		
			while (list($key, $val) = each($ANSWER)) {
				if ($total) {
					$percent =  $file_array[$key] * 100 / $total;
				} else {
					$percent=0;
				}
				$percent_int = floor($percent);
				$percent_float = number_format($percent, 0);
				$anti_percent = (100 - $percent_int);
				$tp += $percent_float;
				print "<tr><td colspan=\"2\"><span class=\"font_poll\">$ANSWER[$key]</span></td></tr>";
				print "<tr><td width=\"100%\"><nobr><img height=9 width=\"$percent_int%\" src=\"graphics/icons/votebar.gif\"><img height=9 width=\"$anti_percent%\" src=\"graphics/icons/votebar_grey.gif\"></nobr></td><td><span class=\"font_poll\">&nbsp;$percent_float%</span></td></tr>"; //<span class=\"font_poll\">&nbsp;$percent_float%</span></td>"; //<td>$file_array[$key]</td></tr>";
			}
		
			print "<tr><td colspan=\"2\"><span class=\"font_poll\"><STRONG>Total votes: $total</STRONG><br><!--<a href=\"default.php?show=all\">View Other Polls</a>--></span></td></tr>"
				."</TABLE>";
		}
	}
	// officer accesspoint
			if ($_SESSION['login_officer']) {
				print "<div align=\"left\" class=\"officer_access\"><a href=\"officers/default.php?action=poll&id=$POLL_NAME\"><img src=\"graphics/icons/paper_edit.gif\" border=0 width='17' height='17' hspace='2' align=\"absmiddle\"><font color=\"#ffffff\">Edit</font></a>&nbsp;<a  href=\"#\" onclick=\"javascript:confirmAction('Really delete poll?','officers/default.php?action=delete&id=$POLL_NAME&type=poll&return=home')\"><img src=\"graphics/icons/paper_remove.gif\" border=0 width='17' height='17' hspace='2' align=\"absmiddle\"><font color=\"#ffffff\">Delete</font></a></div>";
			}
}
// require_once ("common/bot.php");