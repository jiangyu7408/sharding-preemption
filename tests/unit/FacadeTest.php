<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 20:42.
 */
namespace Daemon\Sharding\Resource\ResourceManger;

use Daemon\Sharding\LocalFSPerformer;
use Daemon\Sharding\OwnershipFacade;
use Daemon\Sharding\Resource\Database\ShardConfig;
use Daemon\Sharding\Resource\ResourceManger\Slice\RoundRobin;
use Daemon\Sharding\ResourceFacade;
use Daemon\Sharding\SliceFacade;

/**
 * Class FacadeTest.
 */
class FacadeTest extends \PHPUnit_Framework_TestCase
{
    public function test()
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
        $resourceFacade = new ResourceFacade();
        $collection = $resourceFacade->makeCollection(new ShardConfig(), $allShardSetting);

        $sliceNumber = 2;
        $sliceFacade = new SliceFacade(new RoundRobin());
        $sliceList = $sliceFacade->makeSlice($collection, $sliceNumber);

        $total = 0;
        foreach ($sliceList as $slice) {
            $this->assertGreaterThan(0, count($slice));
            $total += count($slice);
        }
        $this->assertEquals(count($allShardSetting), $total);

        $ownershipFacade = new OwnershipFacade();
        $ownershipFacade->setPerformer(new LocalFSPerformer('/tmp'));
        $ownershipManager = $ownershipFacade->buildManager($collection);
        $ownershipManager2 = $ownershipFacade->buildManager($collection);
        $this->assertEquals($ownershipManager, $ownershipManager2);
        $success = $ownershipManager->acquire();
        $this->assertTrue($success);
        $success = $ownershipManager->acquire();
        $this->assertNotTrue($success);
        $ownershipManager->release();
        $success = $ownershipManager->acquire();
        $this->assertTrue($success);
        $ownershipManager->release();
        try {
            $ownershipManager->release();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \LogicException);
        }
    }

    public function testBadDir()
    {
        $ownershipFacade = new OwnershipFacade();
        try {
            $ownershipFacade->setPerformer(new LocalFSPerformer('/tmp1111'));
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \InvalidArgumentException);
        }
    }

    /**
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../vendor/autoload.php';
    }
}
