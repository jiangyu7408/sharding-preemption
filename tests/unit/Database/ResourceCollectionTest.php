<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 20:27.
 */
namespace Daemon\Sharding\Resource\Database;

use Daemon\Sharding\ResourceFacade;

/**
 * Class ResourceCollectionTest.
 */
class ResourceCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $allShardSetting = [
            'db1' => [
                'host' => '127.0.0.1',
                'port' => '3306',
                'username' => 'hello',
                'password' => 'world',
                'database' => 'db1',
            ],
            'db2' => [
                'host' => '127.0.0.1',
                'port' => '3307',
                'username' => 'hello',
                'password' => 'world',
                'database' => 'db2',
            ],
            'db3' => [
                'host' => '127.0.0.1',
                'port' => '3308',
                'username' => 'hello',
                'password' => 'world',
                'database' => 'db3',
            ],
        ];

        $facade = new ResourceFacade();
        $collection = $facade->makeCollection(new ShardConfig(), $allShardSetting);
        $this->assertEquals(count($allShardSetting), count($collection));
        foreach ($collection as $resource) {
            $this->assertGreaterThan(0, strlen($resource->getUniqueIdentity()));
        }

        $this->assertGreaterThan(0, strlen($collection->getHashValue()));
    }

    /**
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../../vendor/autoload.php';
    }
}
