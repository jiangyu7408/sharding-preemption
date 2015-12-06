<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 14:02.
 */
namespace Daemon\Sharding\Resource\ResourceManger;

use Daemon\Sharding\Resource\ResourceCollection;

/**
 * Class SliceFactory.
 */
class SliceFactory
{
    public function __construct(SliceInterface $sliceInterface)
    {
        $this->performer = $sliceInterface;
    }

    /**
     * SliceFactory constructor.
     *
     * @param ResourceCollection $collection
     * @param int                $sliceNumber
     *
     * @return ResourceCollection[]
     */
    public function make(ResourceCollection $collection, $sliceNumber)
    {
        $this->performer->setData($collection, $sliceNumber);
        $ret = [];
        for ($index = 0; $index < $sliceNumber; $index++) {
            $ret[] = $this->performer->getSlice($index);
        }

        return $ret;
    }
}
