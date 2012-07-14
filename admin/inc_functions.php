<?
//*************************************************//
// MOBMOV
//*************************************************//
session_start();
// GET IP
function getIP (){
    $ip = (getenv('HTTP_X_FORWARDED_FOR'))
            ?  getenv('HTTP_X_FORWARDED_FOR')
            :  getenv('REMOTE_ADDR');
    return $ip;
}


$ip = getIP();
if ($_SERVER['SERVER_NAME']=="test.mobmov.org" || $_SERVER['SERVER_NAME']=="192.168.1.121" || $ip == "192.168.1.1") {
	ini_set('error_reporting', E_ALL & ~ E_NOTICE);
	global $verbosemode;
    $verbosemode = true;
    $stimer = explode( ' ', microtime() );
	$stimer = $stimer[1] + $stimer[0];
}

$verbose[] = "<b>START</b>";

//header("Cache-control: private");
require_once("../common/functions.php");
//bug: on members page, we lose the session data!
while(list($key, $val) = each($_SESSION)){
	$key = "global_".strtolower($key);
	$$key = $val;
    //print $key ." = ". $val . "<br>";
}

$chaptersql = "'". str_replace(",","' OR chapter_id='",$global_chapters)."'";

function chapter_list($chapter_id){
	global $chaptersql;
	db_connect();
	$return="";
	$sql = "SELECT chapter_id,city,state,country FROM chapters WHERE chapter_id =" . $chaptersql . " AND approved='y' ORDER BY country,city";
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)){
    	$it_country = $row['country'];
    	$it_city = $row['city'];
    	$it_state = $row['state'];
    	$it_chapter_id = $row['chapter_id'];
		if ($old_country !== $it_country && $it_country) {
			$return .= "<option value=''>".strtoupper($it_country)."</option>";
			$old_country = $it_country;
		}
		//if ($it_country) { $it_country = ", $it_country"; }
		if ($it_state) { $it_state = ", $it_state";	}
		
		$return .= '<option value="'.$it_chapter_id.'" ';
		if ($chapter_id == $it_chapter_id) {
			$return .= 'selected';
		}
		$return .= '>&nbsp;&nbsp;&nbsp;'.$it_city . $it_state . '</option>';
	}
	return $return;
                                       	
}


function showing_template_fill($template,$showing_id) {
	$sql = "	SELECT 
						UNIX_TIMESTAMP(s.showtime) as showtime,
						sm.movie_title as feature_title,
						sm.movie_desc as feature_desc,
						sm.movie_rating as feature_rating,
						sm.movie_genre as feature_genre,
						sm.movie_runtime as feature_runtime,
						sm.movie_imdb as feature_info,
						sms.movie_title as short_title,
						l.loc_name as location_title,
						l.loc_map as location_url,
						l.loc_instructs as location_instructs,
						l.loc_radio as location_radio,
						c.city as chapter_title,
						CONCAT(c.city, ', ' , c.state) as chapter_location                                    
						FROM showings AS s 
						LEFT JOIN showings_movies AS sm ON s.feature_movie_id = sm.showing_movie_id 
						LEFT JOIN showings_movies AS sms ON s.short_movie_id = sms.showing_movie_id 
						LEFT JOIN chapters_locations AS l ON s.location_id = l.chapter_location_id 
						LEFT JOIN chapters AS c ON s.chapter_id = c.chapter_id 
						
						WHERE s.showing_id ='$showing_id'";

			
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			if ($row) {
				foreach($row AS $key => $val){ 
					$keyfob = strtolower($key);
					$key = "show_".strtolower($key);
				   
					$$key = $val; 
					
					// special cases
					if ($keyfob == "feature_info") { $val = "Info: $val"; }
					if ($keyfob == "location_url") { $val = "Map: $val"; }
					if ($keyfob == "feature_title") { $val = strtoupper($val); }
					
					$template = str_replace("%$keyfob%","$val",$template);
				}
			}
			mysql_free_result($result);
			$template = str_replace("%showing_date%",date("l, M jS",$show_showtime),$template);
			$template = str_replace("%showing_time%",date("g:iA",$show_showtime),$template);
			$subdate = date("D, M jS \@ g:iA",$show_showtime);
			// subject
			if (!$it_subject) {
				$it_subject = "[mobmov] $show_feature_title: $subdate";
			} else {
				$it_subject = "[mobmov] $it_subject";
			}
			
	return $template;
}


function get_showing_subject($id) {
	db_connect();
	$sql = "SELECT (SELECT movie_title FROM showings_movies WHERE showing_movie_id = feature_movie_id),UNIX_TIMESTAMP(showtime) FROM showings WHERE showing_id ='$id'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0){
		$movie = strtoupper(mysql_result($result,0,0));
		$datetime = date("D n/j/y g:iA",mysql_result($result,0,1));
	}
	return "MobMov show: $movie $datetime";
}

?>