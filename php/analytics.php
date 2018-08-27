<?php
if(file_exists('/var/www/ws86us/owa/owa_php.php')){
  require_once('/var/www/ws86us/owa/owa_php.php');

  $owa = new owa_php();
  // Set the site id you want to track
  $owa->setSiteId('97e0e93898c3b09056f86b395d41b5aa');
  // Uncomment the next line to set your page title
  if(isset($name)){
    $owa->setPageTitle($name);
  }
  // Set other page properties
  //$owa->setProperty('foo', 'bar');
  $owa->trackPageView();
}
 ?>
