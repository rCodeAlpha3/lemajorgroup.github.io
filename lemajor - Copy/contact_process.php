<?php
require "PHPMailer/PHPMailerAutoload.php";

function smtpmailer($to, $from, $from_name, $subject, $body)
    {
      
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; 
 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = 'mail.lemajorgroup.com';
        $mail->Port = 465;  
        $mail->Username = 'admin@lemajorgroup.com';
        $mail->Password = 'Admin_pass01';   
   
   //   $path = 'reseller.pdf';
   //   $mail->AddAttachment($path);
   
        $mail->IsHTML(true);
        $mail->From="admin@lemajorgroup.com";
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send())
        {
            $error ="Please try Later, Error Occured while Processing...";
            return $error; 
        }
        else 
        {
            $error = "Thanks You !! Your email is sent.";  
            return $error;
        }
    }
    
    if(isset($_POST['submit'])){
        $secretKey = '6Le8EfoUAAAAAJEDZQtZ2vHsvmH4dtRSPh_rpPWp';
        $captcha = $_POST['g-recaptcha-response'];

        if(!$captcha){
          echo '<p class="alert alert-warning">Please check the the captcha form.</p>';
          exit;
        }



        $ip = $_SERVER['REMOTE_ADDR'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);

        if(intval($responseKeys["success"]) !== 1) {
          echo '<p class="alert alert-warning">Please check the the captcha form.</p>';
        } else {


    $mail_to = "admin@lemajorgroup.com";
        
    # Sender Data
    $subject = trim($_POST["subject"]);
    $name = str_replace(array("\r","\n"),array(" "," ") , strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    
    if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($subject) OR empty($message)) {
        # Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo '<p class="alert alert-warning">Please complete the form and try again.</p>';
        exit;
    }
  
    $error=smtpmailer($mail_to,$email, $name ,$subject, $message);
}
    }
?>

<html>
    <head>
        <title>PHPMailer 5.2 testing from DomainRacer</title>
    </head>
    <body style="background: black;">
        <center><h2 style="padding-top:70px;color: white;"><?php echo $error; ?></h2></center>
    </body>
    
</html>
