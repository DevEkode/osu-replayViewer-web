function checkFileSize(file) {
var max =  52428800; // 50Mo

if (file.size > max) {
    var pathname = window.location.pathname;
    removeHeaders = false;
    window.location.replace(pathname+"?skinError=5");
    document.getElementById("fileToUpload").value = null; // Clear the field.
  }else{
    return true;
  }
}

function checkFileCharacters(file){
  var patt = /['^£$%&*()}{@#~?><>,|=_+¬-]/;
  if(patt.test(file)){
    var pathname = window.location.pathname;
    removeHeaders = false;
    window.location.replace(pathname+"?skinError=4");
    document.getElementById("fileToUpload").value = null; // Clear the field.
  }else{
    return true;
  }

}

function onClick(form){
  console.log(form.parentNode);
  var file = document.getElementById("fileToUpload").files[0];
  if(checkFileSize(file) && checkFileCharacters(file)){
    form.parentNode.submit();
  }
}

window.onchange = function() {
  onClick();
};
