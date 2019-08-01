<?php

namespace MyAtomic\Tests\Unit\Storage\Sharding;

use MyAtomic\Storage\Sharding\ModuloSharding;
use MyAtomic\Storage\Sharding\ShardingStategyInterface;
use MyAtomic\Storage\StorageKey;
use PHPUnit\Framework\TestCase;

class ModuloShardingTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new ModuloSharding();
        $this->assertInstanceOf(ShardingStategyInterface::class, $sut);
    }

    public function testGetShard()
    {
        $sut = new ModuloSharding();

        // 5 modulo 2 == 1
        $shard = $sut->getShard($this->generateStorageKey(5), 1);
        $this->assertEquals(0, $shard);

        // 5 modulo 2 == 1
        $shard = $sut->getShard($this->generateStorageKey(5), 2);
        $this->assertEquals(1, $shard);

        // 6 modulo 2 == 0
        $shard = $sut->getShard($this->generateStorageKey(6), 2);
        $this->assertEquals(0, $shard);

        // 11 modulo 3 == 2
        $shard = $sut->getShard($this->generateStorageKey(11), 3);
        $this->assertEquals(2, $shard);
    }

    protected function generateStorageKey($int)
    {
        $hex= dechex($int);
        $uuid = str_repeat('0', 32-strlen($hex)).$hex;
        return new StorageKey($uuid);
    }
}
