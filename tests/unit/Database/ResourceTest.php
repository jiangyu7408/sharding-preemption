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
 * Class ResourceTest.
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function testShardFactory()
    {
        $setting = [
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

    public function testBadMethod()
    {
        $factory = new ResourceFactory();
        try {
            $factory->make([]);
        } catch (\Exception $e) {
            $this->assertEquals('BadMethodCallException', get_class($e));
        }
    }

    public function testBadKey()
    {
        $setting = [
            'port' => '3306',
            'username' => 'hello',
            'password' => 'world',
            'database' => 'shard_1',
        ];
        $factory = new ResourceFactory();
        $factory->setPrototype(new ShardConfig());
        try {
            $factory->make($setting);
        } catch (\Exception $e) {
            $this->assertEquals('InvalidArgumentException', get_class($e));
        }
    }

    /**
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../../vendor/autoload.php';
    }
}
