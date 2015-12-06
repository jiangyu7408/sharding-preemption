<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 15:56.
 */
namespace Daemon\Sharding\Resource\ResourceManger\Slice;

use Daemon\Sharding\Resource\ResourceCollection;
use Daemon\Sharding\Resource\ResourceManger\SliceInterface;

/**
 * Class RoundRobin.
 */
class RoundRobin implements SliceInterface
{
    /** @var ResourceCollection[] */
    protected $slices;
    /** @var int */
    protected $sliceNumber;

    /**
     * SliceFactory constructor.
     *
     * @param ResourceCollection $collection
     * @param int                $sliceNumber
     */
    public function setData(ResourceCollection $collection, $sliceNumber)
    {
        $this->slices = $this->buildSlices($sliceNumber);
        $this->sliceNumber = $sliceNumber;
        $this->fillSlices($collection);
    }

    /**
     * @param int $sliceIndex
     *
     * @return ResourceCollection
     */
    public function getSlice($sliceIndex)
    {
        if ($sliceIndex >= $this->sliceNumber) {
            throw new \OutOfRangeException('slice index overflow');
        }

        return $this->slices[$sliceIndex];
    }

    /**
     * @param int $sliceNumber
     *
     * @return array
     */
    private function buildSlices($sliceNumber)
    {
        $slices = [];
        for ($i = 0; $i < $sliceNumber; $i++) {
            $slices[$i] = new ResourceCollection();
        }

        return $slices;
    }

    /**
     * @param ResourceCollection $collection
     */
    private function fillSlices(ResourceCollection $collection)
    {
        $sliceIndex = 0;
        foreach ($collection as $resource) {
            $slice = $this->slices[$sliceIndex];
            $slice->add($resource);
            ++$sliceIndex;
            if ($sliceIndex >= $this->sliceNumber) {
                $sliceIndex = 0;
            }
        }
    }
}
