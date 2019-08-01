<?php

namespace MyAtomic\Tests\Unit\Storage\Sharding;

use MyAtomic\Storage\Sharding\ShardingStategyInterface;
use MyAtomic\Storage\Sharding\UnarySharding;
use MyAtomic\Storage\StorageKey;
use PHPUnit\Framework\TestCase;

class UnaryShardingTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new UnarySharding();
        $this->assertInstanceOf(ShardingStategyInterface::class, $sut);
    }

    public function testGetShard()
    {
        $sut = new UnarySharding();

        $shard1 = $sut->getShard($this->generateStorageKey(0), 10);
        $shard2 = $sut->getShard($this->generateStorageKey(2^5), 10);
        $shard3 = $sut->getShard($this->generateStorageKey(2^15), 10);
        $shard4 = $sut->getShard($this->generateStorageKey(2^30), 10);

        $this->assertEquals(1, $shard1);
        $this->assertEquals(1, $shard2);
        $this->assertEquals(1, $shard3);
        $this->assertEquals(1, $shard4);
    }

    protected function generateStorageKey($int)
    {
        $hex= dechex($int);
        $uuid = str_pad($hex, 32, '0'. STR_PAD_LEFT);
        return new StorageKey($uuid);
    }
}
