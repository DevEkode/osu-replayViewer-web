<?php

function getUserInterests($userId){
	$page = file_get_contents('https://osu.ppy.sh/users/'.$userId);
	preg_match("/\"interests\":\".*\",\"occupation\"/", $page, $output_array);
	if(!empty($output_array)){
		$web = explode("\"", $output_array[0]);
		return $web[3];
	}else{
		return "";
	}
}
	
?>

<html>
	<head>
		 <script src="js/request.js"></script> 
	<head>

	<body onload="start()">
		<form id="form" onsubmit="submitted()">
		Osu! player id: 
		<input type="text" name="userId" onkeyup="showUsername(this.value); update()"> <span id="txtHint"></span><br>
		e-mail: 
		<input type="text" name="email" id="email" onkeyup="showEmailValidity(); update()"><span id="emailHint"></span><br>
		password: 
		<input type="password" name="password" id="pass" onkeyup="update()"><br>
		confirm password: 
		<input type="password" name="cPassword" id="confPass" onkeyup="showCheckPass(); update()"> <span id="checkPass"></span><br>
		<input type="submit" value="Submit" id="submitButton">
		</form> 
	</body>
</html>