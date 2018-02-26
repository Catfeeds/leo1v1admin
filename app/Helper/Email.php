<?php
namespace App\Helper;
use Illuminate\Support\Facades\Log ;
use Illuminate\Support\Facades\Redis ;
use \App\Enums as  E;
use \PHPMailer\PHPMailer\PHPMailer ;

class Email{
    /**
     * 理优教研组
     */
    static public function SendMailLeoCom($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "jim@leoedu.com";
        $Password = "xcwen142857";
        $From     = "jim@leoedu.com";
        $FromName = "理优教研组";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey);
        return $ret;
    }

    /**
     * 理优教研组-163
     */
    static public function SendMailLeoCom163($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "leoeduemd@163.com";
        $Password = "yb142857";
        $From     = "leoeduemd@163.com";
        $FromName = "理优教学管理部";
        $MailHost = "smtp.163.com";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey,$MailHost);
        return $ret;
    }

    /**
     * 教学管理事业部教学部
     */
    static public function SendMailJiaoXue($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "leojiaoxuebu@leoedu.com";
        $Password = "leojiaoxuebu123";
        $From     = "leojiaoxuebu@leoedu.com";
        $FromName = "教学管理事业部教学部";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey);
        return $ret;
    }

    /**
     * 理优教学管理部
     */
    static public function SendMailEmd($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "emd@leoedu.com";
        $Password = "emd123456";
        $From     = "emd@leoedu.com";
        $FromName = "理优教学管理部";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey);
        return $ret;
    }

    /**
     * 理优教学管理部-163邮箱
     */
    static public function SendMailEmd163($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "leoeduemd@163.com";
        $Password = "yb142857";
        $From     = "leoeduemd@163.com";
        $FromName = "理优教学管理部";
        $MailHost = "smtp.163.com";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey,$MailHost);
        return $ret;
    }

    /**
     * 理优教学管理部
     */
    static public function SendMail163($Address,$Title,$Message,$IsHtml=true,$AddAddressKey=0){
        $Username = "wg392567893@163.com";
        $Password = "adlovecat123";
        $From     = "wg392567893@163.com";
        $FromName = "理优教学管理部";
        $MailHost = "smtp.163.com";

        $ret = self::SendMail($Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey,$MailHost);
        return $ret;
    }

    /**
     * 发送邮件
     * @param string UserName 邮局用户名
     * @param string Password 邮局密码
     * @param string From 邮件发送者email地址
     * @param string FromName 邮件发送者姓名
     * @param string|array Address 发送邮件的地址
     * @param string  Title 发送邮件的标题
     * @param string  Message 发送邮件的内容
     * @param boolean IsHtml  发送邮件是否为Html的格式
     * @param integer AddAddressKey 当Address为array时，前Key个为发送人，剩余的为抄送人
     * @return
     */
    static public function SendMail(
        $Username,$Password,$From,$FromName,$Address,$Title,$Message,$IsHtml,$AddAddressKey,$MailHost="smtp.leoedu.com"
    ){
        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        //设定时区东八区
        date_default_timezone_set('Asia/Shanghai');

        $mail = new PHPMailer();
        // $mail->SMTPDebug = 2;
        //使用SMTP方式发送
        $mail->IsSMTP();
        //设置编码，否则发送中文乱码
        $mail->CharSet = "UTF-8";
        $mail->Host = $MailHost;

        // 启用SMTP验证功能
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 25;

        $mail->Username = $Username;
        $mail->Password = $Password;

        $mail->From     = $From;
        $mail->FromName = $FromName;

        if (is_array($Address)) {
            foreach ( $Address as $i => $item ){
                if ($i<$AddAddressKey) {
                    //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
                    $mail->AddAddress($item,$item);
                }else{
                    $mail->addCC($item,$item);
                }
            }
        }else{
            $mail->AddAddress($Address, $Address);
        }

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        //是否使用HTML格式
        $mail->IsHTML($IsHtml);
        $mail->Subject = $Title;
        $mail->Body    = $Message;
        //附加信息，可以省略
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
        $ret = $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger(" leo_com:email err: ".json_encode($Address)." :$Title  ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger(" leo_com:email succ: ".json_encode($Address)." :$Title " );
        }

        return $ret;
    }

};