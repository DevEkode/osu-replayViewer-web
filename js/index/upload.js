//modal
var modal;

function openModal(){
  modal = document.getElementById("myModal");
  modal.style.display = "block";
}

function closeModal(){
  modal = document.getElementById("myModal");
  modal.style.display = "none";
}

function submitForm(){
  document.getElementById("upload_box").submit();
}

function setItemTrue(item){
  document.getElementById(item).style["border-color"] = "lightgreen";
}

function setItemFalse(item){
  document.getElementById(item).style["border-color"] = "red";
}

function disableProcessing(){
  document.getElementById("start_processing").disabled = true;
  document.getElementById("checkBox").disabled = true;
}
