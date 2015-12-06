<?php
/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 13:18.
 */
use Daemon\Sharding\PollMySQLOnSingleMachine;
use Daemon\Sharding\Resource\ResourceCollection;

require __DIR__.'/../vendor/autoload.php';

error_reporting(7);
ini_set('display_errors', '1');
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 1);
assert_options(ASSERT_BAIL, 1);

$options = getopt('', ['worker:']);

$workerCount = isset($options['worker']) ? (int) $options['worker'] : 2;

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

$skeleton = new PollMySQLOnSingleMachine($allShardSetting, function (ResourceCollection $slice) {
    $sliceHash = $slice->getHashValue();
    $pid = posix_getpid();
    dump($pid.' acquired ownership on slice['.$sliceHash.'] =>');
    dump($slice->getDigestion());

    dump($pid.' working');
    sleep(5);

    dump($pid.' job done, quit');
});

$skeleton->setWorkerCount($workerCount);
$skeleton->poll();
