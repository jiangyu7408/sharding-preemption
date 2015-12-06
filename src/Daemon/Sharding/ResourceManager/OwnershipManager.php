<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 14:37.
 */
namespace Daemon\Sharding\Resource\ResourceManger;

use Daemon\Sharding\Ownership\OwnershipInterface;
use Daemon\Sharding\Resource\ResourceCollection;

/**
 * Class OwnershipManager.
 */
class OwnershipManager
{
    /** @var OwnershipInterface[] */
    protected $performerList = [];

    /**
     * OwnershipManager constructor.
     *
     * @param OwnershipInterface $prototype
     * @param ResourceCollection $collection
     */
    public function __construct(OwnershipInterface $prototype, ResourceCollection $collection)
    {
        foreach ($collection as $resource) {
            $identity = $resource->getUniqueIdentity();
            if (isset($this->performerList[$identity])) {
                throw new \InvalidArgumentException('conflict resource collection found');
            }
            $this->performerList[$identity] = $performer = clone $prototype;
            $performer->setResource($resource);
        }
    }

    public function __destruct()
    {
        $this->release();
    }

    /**
     * @return bool
     */
    public function acquire()
    {
        foreach ($this->performerList as $performer) {
            if ($performer->acquire() === false) {
                return false;
            }
        }

        return true;
    }

    /**
     */
    public function release()
    {
        foreach ($this->performerList as $performer) {
            $performer->release();
        }
    }
}
