<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmailWithPHPMailer(
    $subject,
    $smtp_host,
    $smtp_port,
    $smtp_user,
    $smtp_password,
    $recipient_email,
    $html
) {

    $mail = new PHPMailer(true);

    //Enable verbose debug output
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP(); //Send using SMTP
    $mail->Host       = $smtp_host; //Set the SMTP server to send through
    $mail->SMTPAuth   = true; //Enable SMTP authentication
    $mail->Username   = $smtp_user; //SMTP username
    $mail->Password   = $smtp_password; //SMTP password
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
    //$smtp_port; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Port       = $smtp_port;

    $mail->SMTPSecure = '';
    $mail->SMTPAutoTLS = false;
    $mail->smtpConnect(
        array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        )
    );

    //Recipients
    $mail->setFrom($smtp_user, 'MFSP Plugin');
    $mail->addAddress($recipient_email);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $html;
    $mail->AltBody = $html;

    $mail->send();
}

function sendSharingURLForSharingUser($fs_object, $sharing, $sharing_user)
{
    $sharing_abs_url = getWebrootURI() . "/mfsp-code/$sharing_user->secret";
    $html = str_replace("{{URL}}", $sharing_abs_url, $sharing->text);

    $smtp_host = get_option('mfsp_setting__smtp_host');
    $smtp_port = intval(get_option('mfsp_setting__smtp_port'));
    $smtp_user = get_option('mfsp_setting__smtp_user');
    $smtp_password = get_option('mfsp_setting__smtp_password');

    $object_type_human = ($fs_object->object_type == "F") ? "File" : "Folder";
    $subject = "Resource has been shared with you: ($object_type_human) $fs_object->object_text";

    try {
        sendEmailWithPHPMailer(
            $subject,
            $smtp_host,
            $smtp_port,
            $smtp_user,
            $smtp_password,
            $sharing_user->email,
            $html
        );
    } catch (Exception $e) {
        return array("success" => false, "msg" => "PHPMailer Error: {$e->getMessage()}");
    }

    dbIncEmailSendedForSharingUser($sharing_user);
    return array("success" => true, "msg" => "OK");
}
