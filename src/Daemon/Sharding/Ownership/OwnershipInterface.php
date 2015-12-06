<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 14:51.
 */

namespace Daemon\Sharding\Ownership;

use Daemon\Sharding\Resource\ResourceInterface;

/**
 * Class OwnershipInterface
 */
interface OwnershipInterface
{
    /**
     * @param ResourceInterface $resource
     */
    public function setResource(ResourceInterface $resource);

    /**
     * @return bool
     */
    public function acquire();

    /**
     * @return bool
     */
    public function release();
}
