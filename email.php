<?
require("common/top.php");

if ($_POST['form']) 
{
	$body = file_get_contents("templates/".$_POST['form']);
    if ($body) {
    	while(list($key, $val) = each($_POST))
        {
    		$key = strtolower($key);
    		$$key = $val;
            $body = str_replace("#$key#","$val",$body);
        }
        if (!$return) {
        	$return = $_SERVER['HTTP_REFERER'];
        }
        if (!$from) {
        	$from = $email;
        }
        if ($required) {
        	$required = split(",",$required);
            foreach ($required as $req) {
            	if (!$$req) {
                	header("Location: $return?error=blank");
                    die();
                }
            }
        }
if ($verify != 'mobmov') {  //isset($verify) && 
    	header("Location: $return?error=blank");
        die();
}

    email($to,$subject,stripslashes($body));

   	}
    if (strstr($return,"?")) {
    	header("Location: $return&success=y");
    } else {
    	header("Location: $return?success=y");
    }
} 
