<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 16:08.
 */
namespace Daemon\Sharding;

use Daemon\Sharding\Resource\ResourceCollection;
use Daemon\Sharding\Resource\ResourceManger\SliceFactory;
use Daemon\Sharding\Resource\ResourceManger\SliceInterface;

/**
 * Class SliceFacade.
 */
class SliceFacade
{
    /**
     * @var SliceFactory
     */
    protected $factory;

    public function __construct(SliceInterface $performer)
    {
        $this->factory = new SliceFactory($performer);
    }

    /**
     * @param ResourceCollection $allResources
     * @param int                $sliceNumber
     *
     * @return Resource\ResourceCollection[]
     */
    public function makeSlice(ResourceCollection $allResources, $sliceNumber)
    {
        return $this->factory->make($allResources, $sliceNumber);
    }
}
