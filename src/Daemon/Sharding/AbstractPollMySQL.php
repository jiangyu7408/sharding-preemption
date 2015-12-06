<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 17:18.
 */
namespace Daemon\Sharding;

/**
 * Class AbstractPollMySQL.
 */
abstract class AbstractPollMySQL extends AbstractPoll
{
    /**
     * @return Resource\ResourceInterface
     */
    protected function getResourcePrototype()
    {
        return new Resource\Database\ShardConfig();
    }

    /**
     * @return Resource\ResourceManger\SliceInterface
     */
    protected function selectSliceStrategy()
    {
        return new Resource\ResourceManger\Slice\RoundRobin();
    }
}
