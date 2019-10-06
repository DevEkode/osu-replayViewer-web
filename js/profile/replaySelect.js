let selectedReplays = [];
let selectedReplaysMd5 = [];

function onCheckboxUpdated(checkbox, replayId, replayMd5 = null) {
    if (checkbox.checked) {
        onCheckReplay(replayId, replayMd5)
    } else {
        onUncheckReplay(replayId, replayMd5)
    }
}

function onCheckReplay(replayId, replayMd5 = null) {
    console.log("Added replay " + replayId + " to list " + replayMd5);
    selectedReplays.push(replayId);
    selectedReplaysMd5.push(replayMd5);
    updateMultiButtons();
}

function onUncheckReplay(replayId, replayMd5 = null) {
    console.log("Removed replay " + replayId + " to list");
    //Remove replay from list
    for (let i = selectedReplays.length - 1; i >= 0; i--) {
        if (selectedReplays[i] === replayId) {
            selectedReplays.splice(i, 1);
            selectedReplaysMd5.splice(i, 1);
        }
    }

    updateMultiButtons();
}

function updateMultiButtons() {
    let bts = document.querySelectorAll("#multi_card_buttons span");
    if (selectedReplays.length >= 1) {
        bts.forEach(function (bt) {
            bt.removeAttribute('disabled');
        })
    } else {
        bts.forEach(function (bt) {
            bt.setAttribute('disabled', true);
        })
    }
}