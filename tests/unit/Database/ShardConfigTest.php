<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 13:48.
 */
namespace Daemon\Sharding\Resource\Database;

use Daemon\Sharding\Resource\ResourceFactory;

/**
 * Class ShardConfigTest.
 */
class ShardConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function testShardFactory()
    {
        $setting = [
            'type' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'username' => 'hello',
            'password' => 'world',
            'database' => 'shard_1',
        ];
        $factory = new ResourceFactory();
        $factory->setPrototype(new ShardConfig());
        $shardConfig = $factory->make($setting);
        $this->assertTrue($shardConfig instanceof ShardConfig);
        $identity = $shardConfig->getUniqueIdentity();
        $this->assertTrue(is_string($identity));
        $this->assertTrue(strlen($identity) > 0);
    }

    /**
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../../vendor/autoload.php';
    }
}
