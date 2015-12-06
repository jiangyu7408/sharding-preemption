<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 13:38.
 */
namespace Daemon\Sharding\Resource\Database;

use Daemon\Sharding\Resource\ResourceInterface;

/**
 * Class ShardConfig.
 */
class ShardConfig implements ResourceInterface
{
    /** @var string */
    public $host;
    /** @var int */
    public $port;
    /** @var string */
    public $username;
    /** @var string */
    public $password;
    /** @var string */
    public $database;

    /**
     * @return string
     */
    public function getUniqueIdentity()
    {
        return "mysql:host={$this->host};port={$this->port};dbname={$this->database}";
    }
}
