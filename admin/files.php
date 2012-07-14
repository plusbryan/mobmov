<?
//*************************************************//
// MOBMOV
//*************************************************//
require_once("inc_functions.php");
$verbose[] = "<b>files.php</b>";

if(!$global_islogged){
	header("Location: index.php");
	exit;
}

# configuration
$show_path     = 0;   # show local path
$show_dotdirs  = 0;   # show and '..'

$topdir = "files/";
$middir = str_replace("..","",$_GET['dir']);
$path = $topdir.$middir; 
?>
<? require_once("inc_top.php"); ?>
 





<br><br>

<table class="files" cellspacing="1">
  <tr>
    <th class="files"><?php if ($show_path == 1) { echo $path; } ?></th>
  </tr>
  <tr>
    <td class="files">
   
<?php

if ($middir) {
	print '<a href="javascript: history.go(-1)"><b>Back</b></a><br/><br/>';
} else {
	print '<a href="desktop.php"><b>Back</b></a><br/><br/>';
}
$dirs = array();
$files = array();

$dir = dir($path);
while ($entry = $dir->read()) {
  if ($entry != "." && substr($entry, -4) != ".php") {
    if (is_file($topdir . '/' . $middir . '/' . $entry)) {
    	$files[] = $entry;
    } else {
      if ($entry != ".." && $entry != ".svn"){
        $dirs[] = $entry;
      }
    }
  }
}
$dir->close();

sort($dirs);
foreach ($dirs as $dir) {
  echo '<a href="files.php?dir=' . $middir .'/'. $dir . '"><img src="images/files/folder.png" align="absmiddle" border=0 style="padding-right:4px;">' . $dir . "</a><br>\n";
}

sort($files);
foreach ($files as $file) {
		$ext = getFileExtension($file);
		switch ($ext){
			case 'png':
		    case 'gif':
		    case 'jpg':
		    case 'psd':
		    case 'bmp':
				$icon='image';
				break;
		    case 'zip':
				$icon='zip';
				break;	
		    case 'xls':
				$icon='excel';
				break;
		 	case 'avi':
		 	case 'mov':
				$icon='film';
				break;
		 	default:
		 		$icon='file';
		}
  echo '<a href="/admin/files' . $middir .'/'. $file . '"><img src="images/files/'.$icon.'.png" align="absmiddle" border=0 style="padding-right:4px;">' . $file . "<br>\n";
}
?>
    </td>
  </tr>
</table>

<br>


 <i>Right-click a file to download it</i><br/><br/>
 Have you made a graphic or video that you want to share here?<br/>Email it to <a href="mailto:info@mobmov.org">info@mobmov.org</a> and we'll pop it in!<br/><br/>
 <font color="gray">All files within are licensed CreativeCommons -ShareAlike NonCommerical. They are strictly for mobmov-related use.</font><br/><br/>
<? require_once("inc_bot.php"); ?>
