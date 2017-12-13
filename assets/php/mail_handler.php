<?php
require_once('email_config.php');
require('phpmailer/PHPMailer/PHPMailerAutoload.php');


$message = [];
$output = [
    'success' => null,
    'messages' => []
    ];


$message['c_email'] = filter_var($_POST['c_email'], FILTER_VALIDATE_EMAIL);
    if (empty($message['c_email'])){
        $output['success'] = false;
        $output['messages'][] = 'invalid name key';
    }

$message['c_name'] = filter_var($_POST['c_name'], FILTER_SANITIZE_STRING);
    if (empty($message['c_name'])){
        $output['success'] = false;
        $output['messages'][] = 'missing name key';
    };

$message['c_message'] = filter_var($_POST['c_message'], FILTER_SANITIZE_STRING);
    if (empty($message['c_message'])){
        $output['success'] = false;
        $output['messages'][] = 'missing name key';
}

if($output['success'] !== null){
        http_response_code(400);
        echo json_encode($output);
        exit();
}
$message['c_message'] = nl2br($message['c_message']);

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;           // Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = 'mikeeulphp@gmail.com';   // SMTP username
$mail->Password = 'michaelahn_lfz94';   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = $message['c_email'];  // sender's email address (shows in "From" field)
$mail->FromName = $message['c_name'];   // sender's name (shows in "From" field)
$mail->addAddress('mikeeulphp@gmail.com', 'Khris Tahl');  // Add a recipient
//$mail->addAddress('ellen@example.com');                        // Name is optional
$mail->addReplyTo($message['c_email']);                          // Add a reply-to address
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $message['c_message'];
$mail->Body    = $message['c_message'];
$mail->AltBody = $message['c_message'];

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

if (!$mail->send()){
    $output['success']=false;
    $output['messages'][]= $mail ->ErrorInfo;
}
else {
    $output['success'] = true;
}

echo json_encode($output);

?>
