<?
//*************************************************//
// MOBMOV
//*************************************************//

require_once("inc_functions.php");
if(!$global_islogged){
	header("Location: index.php");
	exit;
}

db_connect();

$id = $_GET['id'];
if (!$id) {
	if (!$global_superuser) { 
		$showsql = " WHERE movie_show='y'";
	}
	$sql = "SELECT * FROM showings_movies $showsql ORDER BY movie_title";
    $show_all = true;
} else {
	if (!$global_superuser) { 
		$showsql = " AND movie_show='y'";
	}
	$sql = "SELECT * FROM showings_movies WHERE showing_movie_id ='$id' $showsql";
}


$setup = $_GET['setup'];

?>
<? require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0">
<? if (!$setup) { 
	$result = mysql_query($sql);
	?>
              <tr>
                <td valign="top">
                	<table border="0" cellpadding="4" cellspacing="0">
					<?
					if ($show_all) {
                    ?>
                    	<tr>
                      		<td><b>Movie</b></td>
                      		
                      		<td>&nbsp;</td>
					 	 </tr>
					<?
                    
					}
					
					while($row = mysql_fetch_array($result)){ 
						foreach($row AS $key => $val){ 
							$key = strtolower($key);
							$$key = $val; 
						}
                        
                        if ($show_all) {
					?>     
                          	  <tr>
                                <td><?=$movie_title?></td>
                                <td><a href="<?=$movie_imdb?>" target="_blank"><u>Link</u></td>
                                <td>
                                <input name="Button" type="button" class="form_button" value="Edit" onClick="JavaScript:location='movies.php?id=<?=$showing_movie_id?>';">&nbsp;&nbsp;
                                <? if ($global_superuser) { ?><input name="Button" type="button" class="form_button" value="Delete" onClick="JavaScript:location='delete.php?table=showings_movies&col=showing_movie_id&id=<?=$showing_movie_id?>';">&nbsp;&nbsp;<? } ?>
                               
                                </td>
                              </tr>
					  <?
                      	} else {
                        ?>	
                        
                   				<form action="save.php" method="post" enctype="multipart/form-data" name="form1">
                                	<input type="hidden" name="skipid" value="showing_movie_id">
                                    <input type="hidden" name="showing_movie_id" value="<?=$showing_movie_id?>">
                                    <input type="hidden" name="table" value="showings_movies">
                                    <input type="hidden" name="return" value="movies.php">
                                  
                                  <tr>
                                    <td align="right"><b>Title</b></td>
                                    <td><input name="movie_title" type="text" class="form_text" size="45" value="<?=$movie_title?>"></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><b>URL</b></td>
                                    <td><input name="movie_imdb" type="text" class="form_text" size="45" value="<?=$movie_imdb?>"></td>
                                  </tr>
								  <? if ($global_superuser) { ?>
										<tr>
                                    <td align="right"><b>License Cost</b></td>
                                    <td>$<input name="cost" type="text" class="form_text" size="45" value="<?=$cost?>"></td>
                                  </tr>
								  <? } ?>
                                
                                  
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button_big" value="Next"></td>
                                  </tr> 
                     		 	</form>
					  <?
                        }
					  }
					  mysql_free_result($result);
					  ?>
					  
                	</table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
 <? } else { ?>
 <tr>
                <td style="padding-bottom:10px;">
                    <span class="page_head">What will you show?</span>
                </td>
                </tr>
 <tr>
                <td>
                    <? if ($_REQUEST['error'] == "nomovie") {?><span class="error">You must enter the name of a movie</span><? } ?>
                    </td>
              </tr>
 
  <? } ?>
              <tr>
                <td>
                  <table width="595" border="0" cellpadding="0" cellspacing="0">
				  <form name="form2" method="post" action="save.php" enctype="multipart/form-data">
                  <? if ($setup) { ?>
       				  <input type="hidden" name="return" value="locations.php?setup=y&setup_mov=%id%">
                       <? } else { ?>
                        <input type="hidden" name="return" value="movies.php">
                       <? } ?>
                           <input type="hidden" name="table" value="showings_movies">
                    
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
					  
					  
					
						<table border="0" cellspacing="1" cellpadding="1">
                          		
                                  
                                  <tr>
                                    <td align="right" valign="top"><b>Movie Title</b></td>
                                    <td valign="top"><input name="movie_title" id='mtitle' type="text" class="form_text" size="45" value=""></td>
                                  </tr>
                               
                              
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td><input name="Submit" type="submit" class="form_button_big" value="<? if ($setup) { ?>Next<? } else { ?>Update <? } ?>"></td>
                                  </tr> 
                         
                      </table>
					  
					
					  </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
					</form>
                  </table>
                </td>
              </tr>
            </table>
        
        
        <? require_once("inc_bot.php"); ?>