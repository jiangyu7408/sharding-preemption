[![Build Status](https://travis-ci.org/jiangyu7408/sharding-preemption.svg?style=flat-square)](https://travis-ci.org/jiangyu7408/sharding-preemption)
[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square)](https://github.com/jiangyu7408/sharding-preemption)
[![Packagist Version](https://img.shields.io/packagist/v/jiangyu/sharding-preemption.svg?style=flat-square)](https://packagist.org/packages/jiangyu/sharding-preemption)
[![Total Downloads](https://img.shields.io/packagist/dt/jiangyu/sharding-preemption.svg?style=flat-square)](https://packagist.org/packages/jiangyu/sharding-preemption)
[![Code Climate](https://codeclimate.com/repos/566514d8c92a3c3880000919/badges/dd9269505f16881f8df0/gpa.svg?style=flat-square)](https://codeclimate.com/repos/566514d8c92a3c3880000919/feed)
[![Test Coverage](https://codeclimate.com/repos/566514d8c92a3c3880000919/badges/dd9269505f16881f8df0/coverage.svg)](https://codeclimate.com/repos/566514d8c92a3c3880000919/coverage)

# ShardingPreemption
A micro shard preemption framework.

Suppose you have 100 shards, and you have 10 workers to handle all these shards, so each of the workers can handle 10 shards, and each shard can only be handled by 1 worker.


By splitting all 100 shards to 10 slices, and fire 10 workers at the same time, each worker try to occupy a slice, they can take over all those shards finally.

If workers are not working on 1 machine, then some DLM（Distributed Lock Manager）solutions can be imported to reach that goal.

```
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

```
