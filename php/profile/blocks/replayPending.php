<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/osuApiFunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/MysqlAgent.php';

function block_replayPending()
{
    $user_id = filter_var($_SESSION["userId"], FILTER_VALIDATE_INT);

    //Get every replays (not compressed) of this user from the database
    $bdd = new MysqlAgent();
    $bdd_conn = $bdd->connect();

    $stmt = $bdd_conn->prepare("SELECT * FROM requestlist WHERE playerId = ? ORDER BY date desc ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo <<<EOF
    <div class="columns is-desktop is-multiline" id="multi_card_buttons">
                <div class="column is-12">
                    <div class="buttons has-addons">
                        <span onclick="openMultipleModalPendingReplay('profile')" class="button is-outlined" disabled>❌ Cancel</span>
                    </div>
                </div>
EOF;


        while ($row = $result->fetch_assoc()) {
            echo elem_replayLine_pending($row);
        }
    } else {
        echo '<span>No pending replay</span>';
    }

    echo '</div>';
}

function elem_replayLine_pending(array $replay_row)
{
    $beatmap = getBeatmapJSON($replay_row['beatmapId'], getenv('OSU_KEY'));
    $beatmap_name = $beatmap[0]['title'];
    $beatmap_mods = drawMods($replay_row['binaryMods']);
    $time = $replay_row['date'];

    $replayId = $replay_row['replayId'];
    $redirect = "profile";
    $replayMd5 = $replay_row['md5'];

    $gamemode_img = '/images/osuStdr.png';
    $gamemode_name = 'osu!';
    switch ($replay_row['playMod']) {
        case 1:
            $gamemode_img = '/images/osuTaiko.png';
            $gamemode_name = 'osu!Taiko';
            break;
        case 2:
            $gamemode_img = '/images/osuCTB.png';
            $gamemode_name = 'osu!CTB';
            break;
        case 3:
            $gamemode_img = '/images/osuMania.png';
            $gamemode_name = 'osu!Mania';
            break;
    }

    $beatmap_img = "https://b.ppy.sh/thumb/" . $replay_row['beatmapSetId'] . "l.jpg";
    $pending_link = 'http://' . $_SERVER['HTTP_HOST'] . '/progress.php?id=' . $replay_row['replayId'];

    if ($replay_row['currentStatut'] != 4) {
        $barWidth = 20 * ($replay_row['currentStatut'] + 1);
    } else {
        $barWidth = 99;
    }

    echo <<<EOF
    <div class="column is-4-fullhd is-6-desktop is-12-tablet">
                    <div class="card grow">
                        <div class="card-image">
                            <figure class="image is-4by2 container_check">
                                <img src="$beatmap_img" alt="Beatmap background">
                                <div class="b-checkbox checkbox_card">
                                    <input id="checkbox_$replayId" class="styled" type="checkbox" onchange="onCheckboxUpdated(this,'$replayId','$replayMd5')">
                                    <label for="checkbox_$replayId">
                                    </label>
                                </div>
                            </figure>
                            <!--<progress class="progress is-warning progress_card" value="$barWidth" max="100">$barWidth%</progress>-->
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-48x48 tooltip" data-tooltip="$gamemode_name">
                                        <img class="is-rounded has-background-grey-dark" src="$gamemode_img"
                                             alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-4 card_title">$beatmap_name</p>
                                    <p class="subtitle is-6">$beatmap_mods</p>
                                </div>
                            </div>

                            <div class="content">
                                <time datetime="$time">$time</time>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="$pending_link" class="card-footer-item tooltip"
                               data-tooltip="Go to progress page">🔗</a>
                            <a onclick="openModalPendingReplay('$replayId','$redirect','$replayMd5')" class="card-footer-item tooltip" data-tooltip="Cancel">❌</a>
                        </div>
                    </div>
                </div>
EOF;

}