<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 17:38.
 */
namespace Daemon\Sharding;

use Daemon\Sharding\Ownership\OwnershipInterface;
use Daemon\Sharding\Resource\ResourceCollection;
use Daemon\Sharding\Resource\ResourceInterface;
use Daemon\Sharding\Resource\ResourceManger\SliceInterface;

/**
 * Class AbstractPoll.
 */
abstract class AbstractPoll
{
    /** @var array */
    protected $resourceSettingList;
    /** @var \Closure */
    protected $onSuccess;
    /** @var int */
    protected $workerCount;
    /** @var OwnershipFacade */
    protected $ownershipFacade;

    /**
     * AbstractPollMySQL constructor.
     *
     * @param array    $resourceSettingList
     * @param \Closure $onSuccess
     */
    public function __construct(array $resourceSettingList, \Closure $onSuccess)
    {
        $this->resourceSettingList = $resourceSettingList;
        $this->onSuccess = $onSuccess;
        $this->ownershipFacade = new OwnershipFacade();
    }

    /**
     * @param int $count
     */
    public function setWorkerCount($count)
    {
        $this->workerCount = $count;
    }

    public function poll()
    {
        $sliceList = $this->prepare();
        $slice = $this->execute($sliceList);
        if (count($slice) === 0) {
            return;
        }
        $this->onSuccess($slice);
    }

    protected function prepare()
    {
        $resourceFacade = new ResourceFacade();
        $resourcePrototype = $this->getResourcePrototype();
        $resourceCollection = $resourceFacade->makeCollection($resourcePrototype, $this->resourceSettingList);

        $slicePerformer = $this->selectSliceStrategy();
        $sliceFacade = new SliceFacade($slicePerformer);
        $sliceList = $sliceFacade->makeSlice($resourceCollection, $this->workerCount);

        return $sliceList;
    }

    /**
     * @param ResourceCollection[] $sliceList
     *
     * @return ResourceCollection
     */
    protected function execute(array $sliceList)
    {
        $pollPerformer = $this->getPollPerformer();
        $this->ownershipFacade->setPerformer($pollPerformer);

        foreach ($sliceList as $slice) {
            $ownershipManager = $this->ownershipFacade->buildManager($slice);
            if ($ownershipManager->acquire() === false) {
                continue;
            }

            return $slice;
        }

        return new ResourceCollection();
    }

    /**
     * @param ResourceCollection $slice
     */
    protected function onSuccess(ResourceCollection $slice)
    {
        call_user_func($this->onSuccess, $slice);
    }

    /**
     * @return ResourceInterface
     */
    abstract protected function getResourcePrototype();

    /**
     * @return SliceInterface
     */
    abstract protected function selectSliceStrategy();

    /**
     * @return OwnershipInterface
     */
    abstract protected function getPollPerformer();
}
