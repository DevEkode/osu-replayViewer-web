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

function checkAvailability($beatmapSetId){
	$html = file_get_html('https://osu.ppy.sh/beatmapsets/'.$beatmapSetId);
	$is_new_exist=false;
	if($html->find('div#beatmapset-header__availability-info')){
	$is_new_exist=true;
	echo $is_new_exist;
	}
}

checkAvailability(231675);
?>

<html>
	<head>
		<script>
		function showUsername(str) {
			if (str.length == 0) {
				document.getElementById("txtHint").innerHTML = "";
				return;
			} else {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("txtHint").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "php/getUsername.php?q=" + str, true);
				xmlhttp.send();
			}
		}

		function checkPassword(str) {
			if(str.length == 0){
				document.getElementById("checkPass").innerHTML = "";
			} else {
				var pass = document.getElementById("cPassword").value;
				console.log(pass);
				document.getElementById("checkPass").innerHTML = "ehehe";
				/*if(str != pass && str != ""){
					document.getElementById("checkPass").innerHTML = "password doesn't match !";
				}*/
			}
			
		}
		</script>
	<head>

	<body>
		<form>
		Osu! player id: 
		<input type="text" name="userId" onkeyup="showUsername(this.value)"> <span id="txtHint"></span><br>
		e-mail: 
		<input type="text" name="email"><br>
		password: 
		<input type="password" name="password"><br>
		confirm password: 
		<input type="password" name="cPassword" onkeyup="checkPassword(this.value)"> <span id="checkPass"></span><br>
		<input type="submit" value="Submit">
		</form> 
	</body>
</html>