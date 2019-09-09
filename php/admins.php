<?php
$admins = array(
    "3481725"
);

function isAdmin($id)
{
    global $admins;
    if (in_array($id, $admins)) {
        return true;
    } else {
        return false;
    }
}

?>
