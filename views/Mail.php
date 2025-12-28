<?php
   require $_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php";
   include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
   
   use PHPMailer\PHPMailer\PHPMailer;
   $mail = new PHPMailer;
   $mail->isSMTP();
   $mail->SMTPDebug = 0; //set to 0 in runtime and 2 in testing
   $mail->Host = 'smtp.hostinger.com';
   $mail->Port = 587;
   $mail->SMTPAuth = true;
   $mail->Username = "admin@$site_url_short";
   $mail->Password = $email_password;
   $mail->setFrom("admin@$site_url_short", $site_name);
   $mail->addReplyTo("admin@$site_url_short", $site_name);
   $mail->isHTML(true);
   $mail->SMTPKeepAlive = true;
   //$mail->msgHTML(file_get_contents('message.html'), __DIR__);
   //$mail->addAttachment('attachment.txt');
   
    //To send message, do this:
    //$mail->addAddress(htmlentities($_POST["email"]), htmlentities($_POST["name"]));
    //$mail->Subject = "Welcome To $site_name";
    //$mail->Body = $message;
    //$mail1 = $mail->send();
    //check_mail_status($mail1);
    //$mail->clearAddresses();
    
    //or this: ~ 
    /*$mail_xyz = $cm->send_quick_mail($receiver, $subject, $message); 
    check_mail_status($mail_xyz);
    mail->clearAddresses();*/
   
    Class CustomMail extends  PHPMailer {
        public $mail;
        public function inject($obj) {
            $this->mail = $obj;
        }
        
        public function send_quick_mail($receiver, $subject, $message) {
            $this->mail->addAddress($receiver, "");
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;
            return $this->mail->send();
            // remember to clear after calling this function $mail_xyz->clearAddresses();
        }
    }
    
    $cm = new CustomMail; //to use: just declare: $cm->send_quick_mail($receiver, $subject, $message)
    $cm->inject($mail);
   
ini_set("display_errors", 1);

$name = "";
$organisation = "";
$email ="";
$message ="";

if(isset($_POST["quote"])) {
    $name = htmlentities($_POST["name"]);
    $organisation = htmlentities($_POST["organisation"]);
    $email = htmlentities($_POST["email"]);
    $message = htmlentities($_POST["message"]);
}

$mail_body_top = <<<HTML
    <html>
        <head>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/><link rel="stylesheet" href="$site_url/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
        </head>
        <body style ="font-family:Trirong;padding:18px 9px">
            <div style="margin-bottom:18px">
                <img src="$site_url/static/images/logo_rbg.png" style="margin-left:36%;margin-right:36%;width:25%"/>
            </div>
            <h2 style="color:#00008b;font-family:Arimo;text-align:center">$site_name</h2>
HTML;

$mail_body_bottom = "</body></html>";


//Send Quote Message to Admin:
$admin_quote_sent_message = <<<HTML
    $mail_body_top
        <p>Hello Admin, you just received a quote on $site_name:</p>
        <p><b>Name:</b> $name.</p>
        <p><b>Organisation:</b> $organisation.</p>
        <p><b>Email:</b> $email.</p>
        <p><b>Message:</b><br /> $message.</p>
    $mail_body_bottom
HTML;

//headers
$sender = "admin@$site_url_short";

$headers = "From: $site_name <$sender>\r\n";
$headers .="Reply-To: $sender \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html; charset=UTF-8\r\n";

//tester function:
function check_mail_status($mail_xyz) {
    if (!$mail_xyz) {
        echo "<br /><br /><br /><br /> <span style='color:red; margin-left:15px'><br /> <b>Mail not sent. <br />  Mailer Error: ".$mail_xyz->ErrorInfo."  </b> </span>";
    } else {//$err_msg = error_get_last()["message"];
        //$mail_xyz->clearAddresses();
        //echo "<br /><br /><br /><br /> <span style='color:green'> <br /> <b> Mail sent successfully </b><br /> </span>";

        echo <<<HTML
            <div style="display:block;padding:15px;background-color:#6c757d;color:#fff;border:1px solid #fff;border-radius:6px">
                <div style="text-align:right"><i class="la la-times"></i></div>
                <div style="display:block;position:fixed;text-align:center">Message Sent <i class="la la-check"></i>
            </div>
HTML;
    }
}