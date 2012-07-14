<?
// Kick-Ass Rounded Corner Generator
// v0.1

$corner_cache_dir =$_SERVER['DOCUMENT_ROOT']."/generated/corners/";

// set expires header to encourage long client cache
define('YEAR',31556926);
header("Content-type: image/png");
header('Expires: '.gmdate("D, d M Y H:i:s", time() + YEAR).' GMT');
define('ISCOLOR','/(?:(?:[a-f\d]{3}){1,2})$/i');

// which side to generate
switch ($_GET['a']) {
        case 'tl': $side='tl';
        break;
        case 'tr': $side='tr';
        break;
        case 'br': $side='br';
        break;
        case 'bl': $side='bl';
        break;
        default: $side='tl';
}

// the size of the corner
$width = (int)$_GET['w'];
$height = (int)$_GET['h'];
if (!$height && !$width) {
        $height="8";
        $width="8";
} else if (!$height) {
        $height=$width;
}

// the color of the corner
$color = $_GET['c']; //hex only
$bgcolor = $_GET['b'];

if (!$color || !preg_match(ISCOLOR, $color)) {
        $color = "000000";
}
if (!$bgcolor || !preg_match(ISCOLOR, $bgcolor)) {
        $bgcolor = "ffffff";
}

$bgcolort = ' -fill "#'.$bgcolor.'"';

// figure corners
if ($side == "tr") {
        $rotate = '-rotate "90"';
} elseif ($side == "tl") {
        $rotate = '';
} elseif ($side == "bl") {
        $rotate = '-rotate "-90"';
} elseif ($side == "br") {
        $rotate = '-rotate "180"';
}


$filename = $corner_cache_dir.$color."_".$bgcolor."_".$width."_".$height."_".$side.".png";
if (!file_exists($filename)) {
        // make the image
        $command = 'convert -size '.$width.'x'.$height.' xc:none '.$bgcolort.' -draw \'rectangle 0,0 '.$width.','.$height.'\' -fill "#'.$color.'" -draw \'roundRectangle 0,0 '.($width*2).','.($height*2).' '.($width).','.($height).'\' '.$rotate.' '.$filename;
        $result = exec($command);
}
readfile($filename);
?>
