<?
require_once("inc_functions.php");


$resetpass=$_POST['resetpass'];
$resetpass_to=$_POST['resetpass_to'];
if ($resetpass) {
	db_connect();
	$sql = "SELECT member_id FROM members WHERE email = '$resetpass'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)>0) { $member_id = mysql_result($result,0,0);}
    if ($member_id) {
    	if (!$resetpass_to) {
    		$newpassword = makeRandomPassword();
    	} else {
    		$newpassword = $resetpass_to;
    	}
        $sql = "UPDATE members SET password = OLD_PASSWORD('$newpassword') WHERE member_id = '$member_id' LIMIT 1";
        $result = mysql_query($sql);
        email($resetpass,"Your password has been reset","On your request, we have reset your password for the MobMov Admin (http://mobmov.org/admin) to:\n\n$newpassword\n\nIf you did not request your password to be reset, please inform us at info@mobmov.org");
    	$passwordreset = $resetpass;
    } else {
    	$nousererror = true;
    }

}

if($global_islogged){
	header("Location: desktop.php");
	exit;
}

require("inc_top.php"); ?>

<? 
if ($_GET['forgot'] || $nousererror) {
?>
<table border="0" cellpadding="2" cellspacing="2" class="text_body" align="center">
        <form name="form1" method="post" action="index.php">
        
          <tr>
            <td align="right">&nbsp;</td>
            <td align="left"><?
			if($nousererror){
				echo("<strong>Could not find a user with that e-mail...</strong>");
			}
			?>
            </td>
          </tr>
          <tr>
	          <td>
	          <font size=3>Welcome to the Mobmov Driver's area.</font><br/><br/><a href="http://www.mobmov.org">Back to Home</a>
	          </td>
          </tr>
          <tr>
            <td align="right">login email:</td>
            <td align="left"><input name="resetpass" type="text" class="form_text" size="32"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left"><input name="Submit" type="submit" class="form_button" value="Reset &raquo;"> <a href="index.php">Cancel</a></td>
          </tr>
        </form>
      </table>
<?

	
} else {

?>
	  
	  <table border="0" cellpadding="2" cellspacing="2" class="text_body" align="center">
        <form name="form1" method="post" action="logon.php">
          <tr>
            <td align="right">&nbsp;</td>
            <td align="left"><?
			if($_REQUEST['err']=='1'){
				echo("<strong>Invalid Logon or Password, Please try again.</strong>");
			}
            if ($passwordreset) {
            	echo("<strong>Your new password has been mailed to you at: $passwordreset</strong>");
            }
			?>
            </td>
          </tr>
          <tr>
	          <td>
	          <font size=3>Welcome to the Mobmov Driver's area.</font><br/><br/><a href="http://www.mobmov.org">Back to Home</a>
	          </td>
          </tr>
          <tr>
            <td align="right">login email:</td>
            <td align="left"><input name="logon" type="text" class="form_text" size="32"></td>
          </tr>
          <tr>
            <td align="right">password:</td>
            <td align="left"><input name="password" type="password" class="form_text" size="32"></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td align="left"><a href="index.php?forgot=y"><font size="1">forgot your password?</font></a></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left"><input name="Submit" type="submit" class="form_button" value="Logon &raquo;"></td>
          </tr>
        </form>
      </table>
      
      <? 
      
} 
      
      ?>
	  <? require_once("inc_bot.php"); ?>