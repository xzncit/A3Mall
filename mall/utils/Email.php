<?php
namespace mall\utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use think\Db;

class Email {

    public static function send($name="",$subject="",$body=""){
        $content = Db::name("setting")->where(["name"=>"email"])->value("value");
        $setting = json_decode($content,true);
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $setting["address"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $setting["username"];
            $mail->Password   = $setting["password"];
            $mail->SMTPSecure = $setting["is_ssl"] ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $setting["port"];

            //Recipients
            $mail->setFrom($setting["smtp_send"], $setting["smtp_name"]);

            $arr = explode(",",$name);
            foreach($arr as $val){
                $mail->addAddress($val);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        } catch (\Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }

        return true;
    }

}