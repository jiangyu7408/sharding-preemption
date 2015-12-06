[![Build Status](https://travis-ci.org/jiangyu7408/sharding-preemption.svg)](https://travis-ci.org/jiangyu7408/sharding-preemption)
# ShardingPreemption
A micro shard preemption framework.

Suppose you have 100 shards, and you have 10 workers to handle all these shards, so each of the workers can handle 10 shards, and each shard can only be handled by 1 worker.


By spliting all 100 shards to 10 slices, and fire 10 workers at the same time, each worker try to occupy a slice, they can take over all those shards finally.

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
