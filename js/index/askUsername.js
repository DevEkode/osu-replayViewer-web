var isIdOk = false;

function showUsername(str) {
			if (str.length == 0) {
				document.getElementById("txtHint").innerHTML = "";
				isIdOk = false;
				update();
				return;
			} else {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("txtHint").innerHTML = this.responseText;
						if(this.responseText == "this user doesn't exists" || this.responseText == ''){
							isIdOk = false;
							update();
						}else{
							isIdOk = true;
							update();
						}
					}
				};
				xmlhttp.open("GET", "php/getUsername.php?q=" + str, true);
				xmlhttp.send();
			}
}

function update(){
	//Check if the form is filled

	if(isIdOk){
		show("continue_btn");
	} else { hide("continue_btn"); }
}

//-- Hide and seek
function hide(id){
	node = document.getElementById(id);
	if(node){
		node.style.visibility = "hidden";
	}
}

function show(id){
	node = document.getElementById(id);
	if(node){
		node.style.visibility = "visible";
	}
}

function start(){
	hide("continue_btn");
	document.getElementById("txtHint").value = '';
  document.getElementById("newUsername").value = '';
}
