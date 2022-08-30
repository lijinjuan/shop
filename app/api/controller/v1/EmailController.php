<?php

namespace app\api\controller\v1;

use app\lib\exception\ParameterException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use think\facade\Cache;
use think\Request;

/**
 * \app\api\controller\v1\EmailController
 */
class EmailController
{
    /**
     * sendEmailMessage
     * @param \think\Request $request
     */
    public function sendEmailMessage(Request $request)
    {
        $toEmail = $request->param("email", "");
        if ($toEmail == "")
            throw new ParameterException(["errMessage" => "需要输入正确的邮箱..."]);

        $verifyCode = rand(100000, 999999);
        Cache::set($toEmail, $verifyCode, 20 * 60);
        $content = "Hello, welcome to register 【YXG Store】. Your email verification code is " . rand(100000, 999999) . ", please do not disclose it, beware of being scammed.";
        $this->send($toEmail, "email verification", $content);
        return renderResponse();
    }

    /**
     * send
     * @param $toEmail
     * @param $title
     * @param $content
     * @return bool
     */
    protected function send($toEmail, $title, $content)
    {
        $config = config("email");
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['port'];
        $mail->CharSet = "utf-8";
        try {
            $mail->setFrom($config['fromEmail'], $config['fromUserName']); // 设置发件人
            $mail->addAddress($toEmail);     // 添加收件人
            // 邮件内容
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $content;
            $mail->send();
            return true;
        } catch (\Exception $e) {
            $this->error = $mail->ErrorInfo;
            return false;
        }
    }

}