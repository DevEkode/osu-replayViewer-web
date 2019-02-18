<?php
header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/maintenance.css">
	<link rel="icon" type="image/png" href="images/icon.png" />
	<title>Maintenance</title>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134700452-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-134700452-1');
    </script>
</head>

<body>
    <p>
		maintenance in progress, please come back later.
		<img src="images/codevirtuel.jpg">
	</p>
</body>

</html>