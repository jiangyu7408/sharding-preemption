<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 14:05.
 */
namespace Daemon\Sharding\Resource;

/**
 * Class ResourceInterface.
 */
interface ResourceInterface
{
    /**
     * @return string
     */
    public function getUniqueIdentity();
}
