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

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<? if ( $devhost) {?>ABQIAAAARYOLBGUK2J_w_Sm0NvbzPRQdl0sABg63Hik_ZRuhAF9vBqDpRhTy9MQyYmkeeYvTaW-ZI53zFygOxA<? } else { ?>ABQIAAAARYOLBGUK2J_w_Sm0NvbzPRQ4c4oK2UwRPVcK4YEp_ffh3n1hNhSP-xKPRU3o7RzG2hvP_g7D-sntuw<? } ?>" type="text/javascript"></script>
<script type="text/javascript">
    //<![CDATA[

    function load() {
      if (GBrowserIsCompatible()) {
        var $zoom = 2;		// lower = further out
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(42.385208, -71.131797), $zoom);
        var geocoder = new GClientGeocoder();
        
        map.addControl(new GSmallMapControl());
        //map.addControl(new GMapTypeControl());
       
        
         function showAddress(address,infopanel) {
         	 if (geocoder) {
              	geocoder.getLatLng(
                    address,
                    function(point) {
                      if (!point) {
                        //alert(address + " not found");
                      } else {
                      	var icon = new GIcon();
        				//icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png"
						icon.image = "http://www.mobmov.org/graphics/mmmarker.png";
        				icon.iconSize = new GSize(14, 14);
        				icon.iconAnchor = new GPoint(5, 5);
        				icon.infoWindowAnchor = new GPoint(5, 1);
        				//map.setCenter(point, 13);
                        var marker = new GMarker(point,icon);
                        map.addOverlay(marker);
                        GEvent.addListener(marker, "click", function() {
                        	marker.openInfoWindowHtml(address);
                        	}
                        );
                      }
                	}
              	);
             }
          }
		  
		  function draw(lat,lng, info){
			point = new GLatLng(lat,lng );
			//marker = new GMarker(point);

			var icon = new GIcon();
			//icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png"
			icon.image = "http://www.mobmov.org/graphics/mmmarker.png";
			icon.iconSize = new GSize(14, 14);
			icon.iconAnchor = new GPoint(5, 5);
			icon.infoWindowAnchor = new GPoint(5, 1);
			//map.setCenter(point, 13);
			var marker = new GMarker(point,icon);
			map.addOverlay(marker);
			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(info);
				}
			);

        }

<?
// build the js call for gmaps

$sql ="SELECT chapter_id,city,state,country FROM chapters WHERE zip=''";
$result = mysql_query_safe($sql);
while ($row = mysql_fetch_assoc($result)){
	$id = $row['chapter_id'];
    $city = $row['city'];
    $state = $row['state'];
    $country = $row['country'];
    
    $location = $city;
    if ($state) {
    	$location .= ", $state";
    }
    $infopanel = "<div align='left'><font face=arial><b>$location</b></font><br/>$country<br/><br/><a href='#chapter_$id'>more info</a></div>";
    $mobloc = $location . ", " . $country;
    //$all .= $mobloc . "<br>";
 ?>   
    
    showAddress("<?=$mobloc ?>","<?=$infopanel ?>");

    <?
}
mysql_free_result($result);

// for zip codes
$sql ="SELECT c.chapter_id,c.city,c.state,z.lat,z.lon FROM chapters c JOIN zip_code z ON (zip_code = zip) WHERE zip<>''";
$result = mysql_query_safe($sql);
while ($row = mysql_fetch_assoc($result)){
	$id = $row['chapter_id'];
    
    $location = $row['city'];
    if ($state) {
    	$location .= ", ".$row['state'];
    }
    $infopanel = "<div align='left'><font face=arial><b>$location</b></font><br/><br/><a href='#chapter_$id'>more info</a></div>";
    $mobloc = $location . ", " . $country;
    //$all .= $mobloc . "<br>";
 ?>   
    
    draw("<?=$row['lat']?>","<?=$row['lon']?>","<?=$infopanel?>");

    <?
}
mysql_free_result($result);
?>
    
 
      }
    }

    //]]>
    </script>
	<style type="text/css">
    v\:* {
      behavior:url(#default#VML);
    }
    </style>

<?
$body_tag = '<body onload="load()" onunload="GUnload()">';
require_once("common/top.php"); ?>


<span class="header-page">movie mobs around the world</span><br><br>

<div id="map" style="width: 100%; height: 250px; border: 1px solid white; align: right;" align="right"></div>


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