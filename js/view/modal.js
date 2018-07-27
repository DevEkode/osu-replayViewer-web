var modal;

function openModalDelete(){
  modal = document.getElementById("delete_modal");
  modal.style.display = "block";
}

function openModalRerecord(){
  modal = document.getElementById("rerecord_modal");
  modal.style.display = "block";
}

function closeModalDelete(){
  modal = document.getElementById("delete_modal");
  modal.style.display = "none";
}

function closeModalRerecord(){
  modal = document.getElementById("rerecord_modal");
  modal.style.display = "none";
}
