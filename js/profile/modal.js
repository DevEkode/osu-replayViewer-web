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

function openMultipleModalDeleteReplay(redirect) {
    var modal = document.getElementById("delete_multiple_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_delete_multiple_replayId").value = JSON.stringify(selectedReplays);
    document.getElementById("value_delete_multiple_redirect").value = redirect;
}

function closeMultipleModalDeleteReplay() {
    var modal = document.getElementById("delete_multiple_replay_modal");
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

function openMultipleModalGraveyardReplay(redirect) {
    var modal = document.getElementById("graveyard_multiple_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_graveyard_multiple_replayId").value = JSON.stringify(selectedReplays);
    document.getElementById("value_graveyard_multiple_redirect").value = redirect;
}

function closeMultipleModalGraveyardReplay() {
    var modal = document.getElementById("graveyard_multiple_replay_modal");
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

function openMultipleModalPendingReplay(redirect) {
    var modal = document.getElementById("pending_multiple_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_pending_multiple_replayId").value = JSON.stringify(selectedReplays);
    document.getElementById("value_pending_multiple_redirect").value = redirect;
    document.getElementById("value_pending_multiple_md5").value = JSON.stringify(selectedReplaysMd5);
}

function closeMultipleModalPendingReplay() {
    var modal = document.getElementById("pending_multiple_replay_modal");
    modal.style.display = "none";
}

/* Modal replay re-record */

function openModalRerecordReplay(replayId, redirect) {
    var modal = document.getElementById("rerecord_replay_modal");
    modal.style.display = "block";
    document.getElementById("value_rerecord_replayId").value = replayId;
    document.getElementById("value_rerecord_redirect").value = redirect;
}

function closeModalRerecordReplay() {
    var modal = document.getElementById("rerecord_replay_modal");
    modal.style.display = "none";
}