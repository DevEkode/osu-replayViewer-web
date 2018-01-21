<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/search.css">
		<meta charset="utf-8" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head> 
	
	<body>
		<section id="form">
			Enter the id of the player :
			<form class="form-inline" action="/search.php">
				<div class="form-group">
					<input type="text" class="form-control" id="playerId">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</section>
	</body>
</html>

<?php
	$playerId = isset($_GET['playerId']) ? (int)$_GET['playerId'] : 0;
?>