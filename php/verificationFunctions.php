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
  $headers .= "X-Priority: 1 (Highest)\n";
  $headers .= "X-MSMail-Priority: High\n";
  $headers .= "Importance: High\n";

  $return = mail($email,$subject,$message,$headers);
  var_dump($return);
  return $return;
}
sendEmail("codevirtuel@gmail.com","codevirtuel","123");
 ?>
