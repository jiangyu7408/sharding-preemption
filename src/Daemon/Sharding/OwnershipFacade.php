<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 16:35.
 */
namespace Daemon\Sharding;

use Daemon\Sharding\Ownership\OwnershipInterface;
use Daemon\Sharding\Resource\ResourceCollection;
use Daemon\Sharding\Resource\ResourceManger\OwnershipManager;

/**
 * Class OwnershipFacade.
 */
class OwnershipFacade
{
    /**
     * @var OwnershipInterface
     */
    protected $performer;
    /**
     * @var OwnershipManager[]
     */
    protected $managerList = [];

    /**
     * @param OwnershipInterface $performer
     */
    public function setPerformer(OwnershipInterface $performer)
    {
        $this->performer = $performer;
    }

    /**
     * @param ResourceCollection $resourceCollection
     *
     * @return OwnershipManager
     */
    public function buildManager(ResourceCollection $resourceCollection)
    {
        $hashKey = $resourceCollection->getHashValue();
        if (isset($this->managerList[$hashKey])) {
            return $this->managerList[$hashKey];
        }
        $this->managerList[$hashKey] = $manager = new OwnershipManager($this->performer, $resourceCollection);

        return $manager;
    }
}
