<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 15:05.
 */
namespace Daemon\Sharding;

use Daemon\Sharding\Ownership\OwnershipInterface;
use Daemon\Sharding\Resource\ResourceInterface;

/**
 * Class LocalFSPerformer.
 */
class LocalFSPerformer implements OwnershipInterface
{
    /** @var resource */
    protected $fileHandle;
    /** @var string */
    protected $lockContent;
    /** @var string */
    protected $lockName;

    /**
     * LocalFSPerformer constructor.
     *
     * @param $baseDir
     */
    public function __construct($baseDir)
    {
        if (!is_dir($baseDir) || !is_writable($baseDir)) {
            throw new \InvalidArgumentException('bad dir: '.$baseDir);
        }
        $this->baseDir = rtrim($baseDir, '/');
    }

    /**
     * @param ResourceInterface $resource
     */
    public function setResource(ResourceInterface $resource)
    {
        $this->lockContent = $resource->getUniqueIdentity();
        $this->lockName = sprintf('%s/%s.lock', $this->baseDir, md5($this->lockContent));
    }

    /**
     * @return bool
     */
    public function acquire()
    {
        $fileHandle = fopen($this->lockName, 'w');
        $wouldBlock = 0;
        $success = flock($fileHandle, LOCK_EX | LOCK_NB, $wouldBlock);
        if (!$success) {
            return false;
        }

        fwrite($fileHandle, $this->lockContent);
        $this->fileHandle = $fileHandle;

        return true;
    }

    /**
     * @return bool
     */
    public function release()
    {
        if ($this->fileHandle === null) {
            return true;
        }
        $success = flock($this->fileHandle, LOCK_UN);
        fclose($this->fileHandle);
        $this->fileHandle = null;
        if ($success === false) {
            throw new \LogicException('failed to unlock on '.$this->lockName);
        }
        unlink($this->lockName);

        return true;
    }
}
