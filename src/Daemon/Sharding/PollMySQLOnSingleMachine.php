<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 17:50.
 */
namespace Daemon\Sharding;

/**
 * Class PollMySQLOnSingleMachine.
 */
class PollMySQLOnSingleMachine extends AbstractPollMySQL
{
    /**
     * @return Ownership\OwnershipInterface
     */
    protected function getPollPerformer()
    {
        return new LocalFSPerformer('/tmp/lock');
    }
}
