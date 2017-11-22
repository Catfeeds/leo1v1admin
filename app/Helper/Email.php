<?php
namespace App\Helper;
use Illuminate\Support\Facades\Log ;
use Illuminate\Support\Facades\Redis ;
use \App\Enums as  E;

class Email{

    var $MailHost = "";
    var $Username= "";
    var $Password= "";
    var $From= "";
    var $FromName= "";

    public function __contruct(){
        $this->MailHost = "smtp.leoedu.com";
    }

    public function send_mail_leo_com(){
        $this->Username = "jim@leoedu.com";
        $this->Password = "xcwen142857";
        $this->From     = "jim@leoedu.com";
        $this->FromName = "理优教研组";
    }


    public function SendMail( $address ,$title ,$message ,$is_html=true) {
        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = $this->MailHost; // 您的企业邮局域名


        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->SMTPSecure="tls";
        $mail->Username = $this->Username; // 邮局用户名(请填写完整的email地址)
        $mail->Password = $this->Password; // 邮局密码

        $mail->From = $this->From; //邮件发送者email地址
        $mail->FromName = $this->FromName;

        if (is_array($address)) {
            foreach ( $address as $i => $item ){
                if ($i==0) {
                    $mail->AddAddress($item,$item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
                }else{
                    $mail->addCC($item,$item);
                }
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式
        $mail->Subject = $title;
        $mail->Body    = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret = $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger(" leo_com:email err: ".json_decode($address)." :$title  ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger(" leo_com:email succ: ".json_decode($address)." :$title " );
        }
        return  $ret;
    }


};