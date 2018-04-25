<?php
function sendEmail($email,$username,$verfId){
  $link = "https://osureplayviewer.xyz/emailVerification.php?id=".$verfId;

  $subject = "osu!replayViewer - email verification";
  $message = "
  <html>
    <body>
      <p>Hello ".$username." ! please click the link below to continue the verification process</p>

      <a href=".$link."> ".$link." </a>
    </body>

  </html>
  ";

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "X-Priority: 1 (Highest)\n";
  $headers .= "X-MSMail-Priority: High\n";
  $headers .= "Importance: High\n";
  $headers .= "From: osu!replayViewer <codevirtuel@osureplayviewer.xyz> \r\n";

  $return = mail($email,$subject,$message,$headers);
  var_dump($return);
  return $return;
}

function sendPasswordRecoveryEmail($email,$userId,$verfId){
  $link = "https://osureplayviewer.xyz/forgotPassword.php?id=".$userId."&verf=".$verfId;

  $subject = "osu!replayViewer - password reset";
  $message = "
  <html>
    <body>
      <p>Hello ! a password reset was asked on your osu!replayViewer account</p>
      <p>If you have not requested this action, please change your password on your profile</p>

      <p>Click on the link below to continue the password reset</p>
      <a href=".$link."> Reset my password </a>
    </body>

  </html>
  ";

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "X-Priority: 1 (Highest)\n";
  $headers .= "X-MSMail-Priority: High\n";
  $headers .= "Importance: High\n";
  $headers .= "From: osu!replayViewer <codevirtuel@osureplayviewer.xyz> \r\n";

  $return = mail($email,$subject,$message,$headers);
  return $return;
}

function sendTempPassword($email,$password){
  $link = "https://osureplayviewer.xyz/login.php";

  $subject = "osu!replayViewer - your temporary password";
  $message = "
  <html>
    <body>
      <p>Hello ! a password reset was asked on your osu!replayViewer account</p>
      <p>If you have not requested this action, please change your password on your profile</p>

      <p>Here is your temporary password : ".$password."</p>
      <a href=".$link."> Click here to go on the login page </a>
    </body>

  </html>
  ";

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "X-Priority: 1 (Highest)\n";
  $headers .= "X-MSMail-Priority: High\n";
  $headers .= "Importance: High\n";
  $headers .= "From: osu!replayViewer <codevirtuel@osureplayviewer.xyz> \r\n";

  $return = mail($email,$subject,$message,$headers);
  return $return;
}
 ?>
