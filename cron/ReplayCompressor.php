<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/ftp_agent.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/MysqlAgent.php';

class ReplayCompressor
{
    private static $life_days = 30;

    private $ftp_conn;
    private $mysql_conn;

    public function __construct()
    {
        //Connect to FTP
        $this->ftp_conn = new ftp_agent();
        $this->ftp_conn->connect();

        //Connect to Mysql
        $sql = new MysqlAgent();
        $sql->connect();
        $this->mysql_conn = $sql->getMysqlConn();
    }

    /**
     * Generate an array of replays id older than $life_days
     * @return array
     */
    private function getExpiredReplays(): array
    {
        $replay_array = array();

        //Select every replay older than $life_days and not already compressed
        $stmt = $this->mysql_conn->prepare("SELECT * FROM replaylist WHERE date < DATE_SUB(now(), INTERVAL ? DAY) AND compressed IS FALSE");
        $stmt->bind_param("i", self::$life_days);
        $stmt->execute();

        //Get results
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($replay_array, $row['replayId']);
        }

        return $replay_array;
    }

    private function setCompressedStatus(String $replayId, bool $compressed)
    {
        //Set this replay as "compressed" (or not) in the database
        $stmt = $this->mysql_conn->prepare("UPDATE replaylist SET compressed = ? WHERE replayId = ?");
        $stmt->bind_param("is", $compressed, $replayId);
        $stmt->execute();
    }

    /**
     * Compress a replay by deleting videos and keeping only the .osr and .osk
     * @param String $replayId
     */
    public function compressReplay(String $replayId)
    {
        //Fetch and delete every .mp4 in the replay folder
        $files = $this->ftp_conn->listFiles($replayId);

        foreach ($files as $file) {
            if (preg_match('/\.mp4$/i', $file['name'])) {
                $this->ftp_conn->removeFile($replayId . '/' . $file['name']);
            }
        }

        //Set this replay as "compressed" in the database
        $this->setCompressedStatus($replayId, true);
    }

    /**
     * Compress an array of replays
     * @param array $replays
     */
    public function compressReplays(array $replays)
    {
        foreach ($replays as $replay) {
            $this->compressReplay($replay);
        }
    }

    public function compressExpiredReplays()
    {
        $replays = $this->getExpiredReplays();
        foreach ($replays as $replay) {
            $this->compressReplay($replay);
        }
    }
}