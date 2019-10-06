<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

use PalePurple\RateLimit\Adapter\Stash as StashAdapter;
use PalePurple\RateLimit\RateLimit;

class UploadLimiter
{
    private static $INSTANCE = null;

    private $adapter;
    private $rateLimiter;

    public function __construct()
    {
        $stash = new \Stash\Pool(new \Stash\Driver\FileSystem());
        $this->adapter = new StashAdapter($stash);
        $this->rateLimiter = new RateLimit("uploadLimiters", getenv("UPLOAD_LIMIT_PER_DAY"), 86400, $this->adapter);
    }

    public function canUserUpload(): bool
    {
        //Check with ip
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->rateLimiter->check($ip)) {
            return true;
        }
        return false;
    }

    public function getUploadsRemaining(): int
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $this->rateLimiter->getAllowance($ip);
    }

    public function getTimeRemaining()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $this->rateLimiter->getTimeLeft($ip);
    }

    public static function getINSTANCE(): UploadLimiter
    {
        if (is_null(self::$INSTANCE)) {
            self::$INSTANCE = new UploadLimiter();
        }
        return self::$INSTANCE;
    }
}