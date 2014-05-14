<?php
/**
 * User  : Nikita.Makarov
 * Date  : 3/28/14
 * Time  : 7:32 PM
 * E-Mail: nikita.makarov@effective-soft.com
 */
$library_path = implode(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'compiled')) . DIRECTORY_SEPARATOR . 'PHPMailer.';

if (extension_loaded('bz2')) {
    require_once 'phar://' . $library_path . 'bz2';
    echo 'BZ2 Loaded' . PHP_EOL;
} elseif (extension_loaded('zlib')) {
    require_once 'phar://' . $library_path . 'gz';
    echo 'GZ Loaded' . PHP_EOL;
} else {
    require_once 'phar://' . $library_path . 'phar';
    echo 'RAW Loaded' . PHP_EOL;
}

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom('from@example.com', 'First Last');
//Set an alternative reply-to address
$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress('whoto@example.com', 'John Doe');
//Set the subject line
$mail->Subject = 'PHPMailer mail() test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}