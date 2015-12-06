<?php
/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 15:55.
 */
namespace Daemon\Sharding\Resource\ResourceManger;

use Daemon\Sharding\Resource\ResourceCollection;

/**
 * Class SliceFactory.
 */
interface SliceInterface
{
    /**
     * SliceFactory constructor.
     *
     * @param ResourceCollection $collection
     * @param int                $sliceNumber
     */
    public function setData(ResourceCollection $collection, $sliceNumber);

    /**
     * @param int $sliceIndex
     *
     * @return ResourceCollection
     */
    public function getSlice($sliceIndex);
}
