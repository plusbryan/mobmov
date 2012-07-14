<? $chapter_id = $_GET['id'];
 if ($chapter_id) {
 
 ?>
 <? require("common/top.php"); ?>
	<?
	$sql = "SELECT * FROM chapters WHERE chapter_id='$chapter_id' AND approved='y'"; //
	$result = mysql_query_safe($sql); 
	$chapter = mysql_fetch_assoc($result);
	mysql_free_result($result);
	
	?>
	 
<span class="header-page">chapter: <?=$chapter['city']?><?if ($chapter['state']) {?>, <?=$chapter['state']?><? } ?></span><br><br>
 <?=$chapter['intro']?>
 <br><br>
 <a href="venues.php">Back to Chapter List</a>
 
 
 <?
	
 } else {
?>

<? require_once("common/top.php"); ?>


<span class="header-page">MobMovs Around The Wold</span><br><br>


<?

$sql = "SELECT c.*,count(ml.link_id) AS count FROM chapters AS c,mailinglist_chapters AS ml WHERE ml.chapter_id=c.chapter_id GROUP BY c.chapter_id ORDER BY c.country DESC, c.state, c.city";
$result = mysql_query_safe($sql);
$laststate="";
$lastcountry="";
while ($row = mysql_fetch_assoc($result)){
    $chapter_id = $row['chapter_id'];
    $count = $row['count'];
    $city = $row['city'];
    $state = $row['state'];
    $country = $row['country'];
    $coordinator = $row['coord_id'];
    
    $location = capitalize($city).", ";
    if (!$state) {
        $location .= capitalize($country);
    } else {
    	$location .= strtoupper($state);
    }
    
    if ($lastcountry != $country) {
    	$lastcountry = $country;
    	$laststate = "";
        if ($lastcountry) {
        	print "</ul>";
        }
        print "<br><b><font color='#F4AF4E' size=4>$country</font></b><br><ul>";
    }
    
    if ($laststate != $state) {
    	$laststate = $state;
        if ($laststate) {
        	print "</ol>";
        }
        print "<br><br><b><font size=3>$state</font></b><br><ol>";
    }
    
    
?>
		
				
<br><b><a name="chapter_<?=$chapter_id?>"></a><? if ($row['intro']) {?><a href='venues.php?id=<?=$chapter_id?>'><? }?><?=$city?><? if ($row['intro']) {?></a><? }?></b>&nbsp;<? if ($count > 50) { ?><font color="grey">(<?=$count?> members)</font><? } else { ?><font color="grey"></font><? } ?>&nbsp;&nbsp;<a href="signup.php?preselect_chapter=<?=$chapter_id?>"><font color="grey" size=1>sign up</font></a>&nbsp;&nbsp;<a href="drive.php?preselect_chapter=<?=$chapter_id?>"><font color="grey" size=1>join as a driver</font></a>&nbsp;&nbsp;<a href="contact.php?driver=<?=$coordinator?>"><font color="grey" size=1>contact driver</font></a></li>

				
<? 
                
}
mysql_free_result($result);
                
?>
</ul></ul>

<? } ?>

<? require_once("common/bot.php"); ?>