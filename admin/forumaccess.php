<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");

if(!$global_islogged){
	header("Location: index.php");
	exit;
}
//db_connect();
		


require_once("inc_top.php"); ?>

			<table border="0" cellspacing="0" cellpadding="0">

              <tr>
                <td valign="top">
                <? if ($message) { ?>
                	<br/><?=$message?><br/><br/>
                	<? } else { ?>
                	Click <a href='http://mobmov.lefora.com/forum/'>here</a> to continue to the forum.
                	<? }  ?>
                
                </td>
              </tr>
            </table>
        
        
        <? require_once("inc_bot.php"); ?>