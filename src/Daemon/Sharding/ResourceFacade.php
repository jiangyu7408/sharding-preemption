<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 16:14.
 */
namespace Daemon\Sharding;

use Daemon\Sharding\Resource\ResourceCollection;
use Daemon\Sharding\Resource\ResourceFactory;
use Daemon\Sharding\Resource\ResourceInterface;

/**
 * Class ResourceFacade.
 */
class ResourceFacade
{
    /**
     * @param ResourceInterface $prototype
     * @param array             $allSetting
     *
     * @return ResourceCollection
     */
    public function makeCollection(ResourceInterface $prototype, array $allSetting)
    {
        $factory = new ResourceFactory();
        $factory->setPrototype($prototype);
        $collection = new ResourceCollection();
        foreach ($allSetting as $setting) {
            $resource = $factory->make($setting);
            $collection->add($resource);
        }

        return $collection;
    }
}
