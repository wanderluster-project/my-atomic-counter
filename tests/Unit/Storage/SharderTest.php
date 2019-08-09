<?php

namespace MyAtomic\Tests\Unit\Storage;

use MyAtomic\Storage\Sharder;
use MyAtomic\Storage\ShardingStrategy\JumpHashSharding;
use MyAtomic\Storage\StorageKey;
use PHPUnit\Framework\TestCase;

class SharderTest extends TestCase
{
    public function testGetShard(){
        $storageKey = new StorageKey('test');
        $shardingStrategy = new JumpHashSharding();
        $numberOfShards = 10;
        $shard = $shardingStrategy->getShard($storageKey, $numberOfShards);

        $sut = new Sharder($shardingStrategy, $numberOfShards);
        $this->assertEquals($shard, $sut->getShard($storageKey));
    }

    public function testGetNumberOfShards(){
        $shardingStrategy = new JumpHashSharding();
        $numberOfShards = 10;

        $sut = new Sharder($shardingStrategy, $numberOfShards);
        $this->assertEquals($numberOfShards, $sut->getNumberOfShards());
    }
}