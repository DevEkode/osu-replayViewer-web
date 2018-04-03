<?php
function sendEmail($email,$username,$verfId){
  $link = "https://osureplayviewer.xyz/emailVerification?id=".$verfId;

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

  mail($email,$subject,$message,$headers);
}
 ?>
