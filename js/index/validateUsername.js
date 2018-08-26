var result;
var id;

function userExists(str){
  if (str.length == 0) {
    return false;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText == "this user doesn't exists" || this.responseText == ''){
          result = false;
        }else{
          result = true;
        }
      }
    };
    xmlhttp.open("GET", "php/getUsername.php?q=" + str, false);
    xmlhttp.send();
  }
}

function getUserId(str){
  if (str.length == 0) {
    id = 1;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText == "this user doesn't exists" || this.responseText == ''){
          id = 1;
        }else{
          id = this.responseText;
        }
      }
    };
    xmlhttp.open("GET", "php/getUserId.php?q=" + str, false);
    xmlhttp.send();
  }
}

function validateName(){
  var str = document.getElementById("newUsername").value;
  userExists(str);
  console.log("user exists : "+result);
  return result;
}

function updatePicture(){
  var str = document.getElementById("newUsername").value;
  var url = "https://a.ppy.sh/";

  if (str.length == 0) {
    document.getElementById("userImage").src=url+"1";
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("userImage").src=url+this.responseText;
      }
    };
    xmlhttp.open("GET", "php/getUserId.php?q=" + str, true);
    xmlhttp.send();
  }
}
