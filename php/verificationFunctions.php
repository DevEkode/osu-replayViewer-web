<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$server = "https://unstable.osureplayviewer.xyz";


function sendEmail($email,$username,$verfId){
  global $server;

  require_once $_SERVER['DOCUMENT_ROOT'].'/secure/smtp.php';
  $link = $server."/emailVerification.php?id=".$verfId;
  $mail = new PHPMailer(true);
  //Server settings
  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = $SMTP_host;                             // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = $SMTP_user;                 // SMTP username
  $mail->Password = $SMTP_pass;                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $SMTP_port;                             // TCP port to connect to

  $mail->setFrom('codevirtuel@osureplayviewer.xyz', 'osu!replayViewer');
  $mail->addAddress($email, 'user');

  //Content
  $mail->isHTML(true);
  $mail->Subject = "osu!replayViewer - email verification";

  $message = "
      <p>Hello ".$username." ! please click the link below to continue the verification process</p>

      <a href=".$link."> ".$link." </a>
  ";

  $mail->Body = $message;
  $mail->send();
}

function sendPasswordRecoveryEmail($email,$userId,$verfId){
  global $server;

  require_once $_SERVER['DOCUMENT_ROOT'].'/secure/smtp.php';
  $link = $server."/forgotPassword.php?id=".$userId."&verf=".$verfId;
  $mail = new PHPMailer(true);
  //Server settings
  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = $SMTP_host;                             // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = $SMTP_user;                 // SMTP username
  $mail->Password = $SMTP_pass;                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $SMTP_port;                             // TCP port to connect to

  $mail->setFrom('codevirtuel@osureplayviewer.xyz', 'osu!replayViewer');
  $mail->addAddress($email, 'user');

  //Content
  $mail->isHTML(true);
  $mail->Subject = "osu!replayViewer - password reset";

  $message = "
      <p>Hello ! a password reset was asked on your osu!replayViewer account</p>
      <p>If you have not requested this action, please change your password on your profile</p>

      <p>Click on the link below to continue the password reset</p>
      <a href=".$link."> Reset my password </a>
  ";

  $mail->Body = $message;
  $mail->send();
}

function sendTempPassword($email,$password){
  global $server;

  require_once $_SERVER['DOCUMENT_ROOT'].'/secure/smtp.php';
  $link = $server."/login.php";
  $mail = new PHPMailer(true);
  //Server settings
  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = $SMTP_host;                             // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = $SMTP_user;                 // SMTP username
  $mail->Password = $SMTP_pass;                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $SMTP_port;                             // TCP port to connect to

  $mail->setFrom('codevirtuel@osureplayviewer.xyz', 'osu!replayViewer');
  $mail->addAddress($email, 'user');

  //Content
  $mail->isHTML(true);
  $mail->Subject = "osu!replayViewer - your temporary password";

  $message = "
      <p>Hello ! a password reset was asked on your osu!replayViewer account</p>
      <p>If you have not requested this action, please change your password on your profile</p>

      <p>Here is your temporary password : ".$password."</p>
      <a href=".$link."> Click here to go on the login page </a>
  ";

  $mail->Body = $message;
  $mail->send();
}

function sendDeleteVerification($email,$userId,$deleteVerfId){
  global $server;
  
  require_once $_SERVER['DOCUMENT_ROOT'].'/secure/smtp.php';
  $link = $server."/php/profile/verfDeleteProfile.php?userId=$userId&id=$deleteVerfId";
  $mail = new PHPMailer(true);
  //Server settings
  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = $SMTP_host;                             // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = $SMTP_user;                 // SMTP username
  $mail->Password = $SMTP_pass;                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $SMTP_port;                             // TCP port to connect to

  $mail->setFrom('codevirtuel@osureplayviewer.xyz', 'osu!replayViewer');
  $mail->addAddress($email, 'user');

  //Content
  $mail->isHTML(true);
  $mail->Subject = "osu!replayViewer - Account deletion request";

  $message = "
      <p>Hello ! a deletion has been asked on your osu!replayViewer account</p>
      <p>If you have not requested this action, please change your password on your profile</p>

      <a href=".$link.">Click here to acknowledge this request</a>
  ";

  $mail->Body = $message;
  $mail->send();
}
 ?>
