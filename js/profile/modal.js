function openModalDelete(){
  var modal = document.getElementById("delete_modal");
  modal.style.display = "block";
}

function closeModalDelete(){
  var modal = document.getElementById("delete_modal");
  modal.style.display = "none";
}

/* Modal replay delete */

function openModalDeleteReplay(replayId, redirect) {
    var modal = document.getElementById("delete_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_replayId").value = replayId;
    document.getElementById("value_redirect").value = redirect;
}

function closeModalDeleteReplay() {
    var modal = document.getElementById("delete_replay_modal");
    modal.style.display = "none";
}

/* Modal replay graveyard */

function openModalGraveyardReplay(replayId, redirect) {
    var modal = document.getElementById("graveyard_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_replayId").value = replayId;
    document.getElementById("value_redirect").value = redirect;
}

function closeModalGraveyardReplay() {
    var modal = document.getElementById("graveyard_replay_modal");
    modal.style.display = "none";
}