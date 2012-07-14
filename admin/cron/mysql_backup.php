<?php
error_reporting(E_ALL ^ E_NOTICE);
/*

Quickly and easily backup your MySQL database and have the tgz emailed to you.  You need PEAR installed with the Mail and Mail_Mime packages installed. Read more about PEAR here: http://pear.php.net. This will work in any *nix enviornment. Make sure you have write access to your /tmp directory.

*/

//require_once('Mail.php');
//require_once('Mail/mime.php');

require($_SERVER['DOCUMENT_ROOT']."/mobmov.org/common/class.phpmailer.php");
$mail = new PHPMailer();

echo "test\n";

// mysql & minor details..
$tmpDir = "/tmp/";
$user = "324642_mobmov";
$password = "6xRm8911LG";
$dbName = "324642_mobmov";
$prefix = "";

// email settings...
$to = "info@mobmov.org";
$from = "info@mobmov.org";
$subject = "mobmov database backup - ".date('Y-m-d');
$sqlFile = $tmpDir.$prefix.date('Y_m_d').".sql";
$attachment = $tmpDir.$prefix.date('Y_m_d').".tgz";

$creatBackup = "mysqldump -u$user -p$password -h 64.49.219.212 $dbName > $sqlFile";
$createZip = "tar cvzf $attachment $sqlFile";
exec($creatBackup);
exec($createZip);
//echo "zip created: $creatBackup\n";

$headers = array('From' => $from, 'Subject' => $subject);
$textMessage = "backup from ".date('Y-m-d')." of $dbName";
$htmlMessage = $textMessage;

/*
$mime = new Mail_Mime("n");
$mime->setTxtBody($textMessage);
$mime->setHtmlBody($htmlMessage);
$mime->addAttachment($attachment, 'application/x-tar-gz');
$body = $mime->get();
$hdrs = $mime->headers($headers);
$mail = &Mail::factory('mail');
$mail->send($to, $hdrs, $body);
echo "mail sent\n";
*/

$mail->From     = $from;
$mail->FromName = "Backup";
$mail->AddAddress($to, "Backup");
$mail->Subject = $subject;
$mail->Body    = $textMessage;
$mail->AddAttachment($attachment, "backup_".$prefix."_".date('Y_m_d').".tgz");  // optional name



 if(!$mail->Send()) {
        echo "There has been a mail error sending<br>";
}
    // Clear all addresses and attachments for next loop
    $mail->ClearAddresses();
    $mail->ClearAttachments();

    unlink($sqlFile);
unlink($attachment);

echo "done";

?>
