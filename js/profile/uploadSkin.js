function checkFileSize(file) {
var max =  52428800; // 50Mo

if (file.size > max) {
    var pathname = window.location.pathname;
    removeHeaders = false;
    window.location.replace(pathname+"?skinError=5");
    document.getElementById("fileToUpload").value = null; // Clear the field.
  }else{
    document.getElementById("submit_skin").submit();
  }
}

function onClick(){
  var file = document.getElementById("fileToUpload").files[0];
  checkFileSize(file);
}

window.onchange = function() {
  onClick();
};
