<?php
function block_replayDatabase()
{
    $user_id = filter_var($_SESSION["userId"], FILTER_VALIDATE_INT);

    //Get every replays (not compressed) of this user from the database
    $bdd = new MysqlAgent();
    $bdd_conn = $bdd->connect();

    $stmt = $bdd_conn->prepare("SELECT * FROM replaylist WHERE userId = ? AND compressed IS FALSE");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        //
    }
}

function elem_replayLine(array $replay_row)
{

}