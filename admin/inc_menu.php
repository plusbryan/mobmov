<link href="css/body.css" rel="stylesheet" type="text/css">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table border="0" cellpadding="1" cellspacing="0">
     
        <tr>
        
        <td colspan="2" class="font-b"><a href="desktop.php" class="font-b">Home</a></td>
        </tr>
        
        
        <!--<tr>
        
        <td colspan="2" class="font-b"><a href="http://www.mobmov.org/community" target="_blank" class="font-b">Forum</a></td>
        </tr>-->
      <tr>
        
        <td colspan="2" class="font-b"><a href="showings.php" class="font-b">Shows</a></td>
        </tr>
        <? if($global_superuser){ ?>
        <tr>
        
        <td colspan="2" class="font-b"><a href="movies.php" class="font-b">Movies</a></td>
        </tr>
        <? } ?>
        <tr>
        
        <td colspan="2" class="font-b"><a href="locations.php" class="font-b">Locations</a></td>
        </tr>
        <tr>
        
        <td colspan="2" class="font-b"><a href="mailings.php" class="font-b">Mailings</a></td>
        </tr>
        <? if($global_superuser){ ?>
        <tr>
        
        <td colspan="2" class="font-b"><a href="members.php" class="font-b">Members</a></td>
        </tr>
         <? } ?>
        <tr>
        
        <td colspan="2" class="font-b"><a href="chapters.php" class="font-b"><? if($global_superuser){ ?>Chapters<? } else {?>My Chapter<? } ?></a></td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        </tr>
      <tr>
        
        <td colspan="2"><a href="logout.php" class="font-b">Logout</a></td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        
        <td colspan="2"><a href="http://www.mobmov.org" class="font-b"><b>Return</b></a></td>
        </tr>
    </table></td><td>&nbsp;&nbsp;</td>
  </tr>
</table>
