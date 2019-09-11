let selectedReplays = [];

function onCheckboxUpdated(checkbox, replayId) {
    if (checkbox.checked) {
        onCheckReplay(replayId)
    } else {
        onUncheckReplay(replayId)
    }
}

function onCheckReplay(replayId) {
    console.log("Added replay " + replayId + " to list");
    selectedReplays.push(replayId);
    updateMultiButtons();
}

function onUncheckReplay(replayId) {
    console.log("Removed replay " + replayId + " to list");
    //Remove replay from list
    for (let i = selectedReplays.length - 1; i >= 0; i--) {
        if (selectedReplays[i] === replayId) {
            selectedReplays.splice(i, 1);
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