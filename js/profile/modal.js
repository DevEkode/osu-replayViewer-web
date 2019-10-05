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
    document.getElementById("value_delete_replayId").value = replayId;
    document.getElementById("value_delete_redirect").value = redirect;
}

function closeModalDeleteReplay() {
    var modal = document.getElementById("delete_replay_modal");
    modal.style.display = "none";
}

/* Modal replay graveyard */

function openModalGraveyardReplay(replayId, redirect) {
    var modal = document.getElementById("graveyard_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_graveyard_replayId").value = replayId;
    document.getElementById("value_graveyard_redirect").value = redirect;
}

function closeModalGraveyardReplay() {
    var modal = document.getElementById("graveyard_replay_modal");
    modal.style.display = "none";
}

/* Modal replay pending cancel */

function openModalPendingReplay(replayId, redirect, md5) {
    var modal = document.getElementById("pending_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_pending_replayId").value = replayId;
    document.getElementById("value_pending_redirect").value = redirect;
    document.getElementById("value_pending_md5").value = md5;
}

function closeModalPendingReplay() {
    var modal = document.getElementById("pending_replay_modal");
    modal.style.display = "none";
}