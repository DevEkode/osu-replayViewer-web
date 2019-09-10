<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/osuApiFunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/MysqlAgent.php';

function block_replayDatabase()
{
    echo <<<EOF
    <div class="columns is-desktop is-multiline">
                <div class="column is-12">
                    <div class="buttons has-addons">
                        <span class="button is-outlined" disabled>⚰️Send to graveyard</span>
                        <span class="button is-outlined" disabled>🗑️ Delete</span>
                    </div>
                </div>
EOF;


    $user_id = filter_var($_SESSION["userId"], FILTER_VALIDATE_INT);

    //Get every replays (not compressed) of this user from the database
    $bdd = new MysqlAgent();
    $bdd_conn = $bdd->connect();

    $stmt = $bdd_conn->prepare("SELECT * FROM replaylist WHERE userId = ? AND compressed IS FALSE ORDER BY date desc ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo elem_replayLine_posted($row);
    }
    echo '</div>';
}

function elem_replayLine_posted(array $replay_row)
{
    $beatmap = getBeatmapJSON($replay_row['beatmapId'], getenv('OSU_KEY'));
    $beatmap_name = $beatmap[0]['title'];
    $beatmap_mods = drawMods($replay_row['binaryMods']);
    $time = $replay_row['date'];

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

    echo <<<EOF
    <div class="column is-4-fullhd is-6-desktop is-12-tablet">
                    <div class="card grow">
                        <div class="card-image">
                            <figure class="image is-4by2 container_check">
                                <img src="$beatmap_img" alt="Placeholder image">
                                <div class="b-checkbox checkbox_card">
                                    <input id="checkbox" class="styled" type="checkbox">
                                    <label for="checkbox">
                                    </label>
                                </div>
                            </figure>
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
                            <a href="#" class="card-footer-item tooltip"
                               data-tooltip="Copy link">🔗</a>
                            <a href="#" class="card-footer-item tooltip"
                               data-tooltip="Send to graveyard">⚰️</a>
                            <a href="#" class="card-footer-item tooltip" data-tooltip="Delete">🗑️</a>
                        </div>
                    </div>
                </div>
EOF;

}