<?
// MAILER

require_once("../common/class.phpmailer.php");

	$mail = new PHPMailer();
    $mail->From     = "info@electricfox.com";
    $mail->FromName = "the driver";
    $mail->Host     = "localhost";
    $mail->AddAddress("notbryan@gmail.com");
    //$mail->AddAddress("support@vortechhosting.com");
    $mail->Subject = "Testing the broken queue 2";
    $mail->Body = nl2br("The queue is working now!");
    if ($mail->Send()) {
    
    print "sent!";
    
    } else {
    	print "error";
    }