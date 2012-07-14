<? $verbose[] = "<b>inc_top</b>"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<HEAD>
<TITLE>MobMov: Driver's Garage</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="css/body.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="common/bubbles.js"></script>
<script type="text/javascript">
window.onload=function(){enableTooltips("content")};
</script>
<script type="text/javascript" src="common/calendarDate.js"></script>
<? if ($page_refresh) { ?>
<meta http-equiv="refresh" content="<?=$page_refresh?>">
<? } ?>
</HEAD>
<BODY bgcolor="#333333">
                                        
<TABLE cellSpacing=0 cellPadding=4 align=center border=0 bgcolor="#cccccc">
    <TR>
      <TD align="right" bgcolor="#333333">
      	<a href="desktop.php"><span style="font-size:20pt;color:white;margin:4px;">mobmov drivers</span></a>
      </TD>
    </TR>
    <TR>
      <TD valign="top">
      	<table border="0" cellpadding="4" cellspacing="0" bgcolor="#ffffff">
          <tr>
            <? if($global_islogged){ ?><td valign="top" bgcolor="#cccccc"><!--#ffdec7--><? require ('inc_menu.php')?></td><? } ?>
            <td>&nbsp;</td>
            <td width="650" valign="top"><? if ($status_message) {?><div class="status_message"><?=$status_message?></div><? } ?><br>
            
            <div id="content">