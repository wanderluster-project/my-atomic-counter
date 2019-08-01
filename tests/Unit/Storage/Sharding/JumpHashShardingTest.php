<?php

namespace MyAtomic\Tests\Unit\Storage\Sharding;

use MyAtomic\Storage\Sharding\JumpHashSharding;
use MyAtomic\Storage\Sharding\ShardingStategyInterface;
use MyAtomic\Storage\StorageKey;
use PHPUnit\Framework\TestCase;

class JumpHashShardingTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new JumpHashSharding();
        $this->assertInstanceOf(ShardingStategyInterface::class, $sut);
    }

    public function testJHashDistribution()
    {
        // Testing that the JumpHash is roughly uniform
        $counts = [0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0, 7=>0, 8=>0, 9=>0];
        $i = 0;
        $max = 2000000;
        while ($i<$max) {
            $shard = jchash($i, 10);
            $counts[$shard]++;
            $i++;
        }
        $expected = $max/10;
        $delta= .001;
        foreach ($counts as $key => $count) {
            $this->assertTrue((($count/$expected)-1) < $delta);
        }
    }

    public function testJHashMovePartition()
    {
        // test that minimal movement
        $i=0;
        $max = 2000000;
        $moved = 0;
        while ($i<$max) {
            $shard10 = jchash($i, 1);
            $shard11 = jchash($i, 2);
            if ($shard10 !== $shard11) {
                $moved++;
            }
            $i++;
        }
        $this->assertTrue(($moved/$max) < 1.01*(1/2));


        // test that minimal movement
        $i=0;
        $max = 2000000;
        $moved = 0;
        while ($i<$max) {
            $shard10 = jchash($i, 10);
            $shard11 = jchash($i, 11);
            if ($shard10 !== $shard11) {
                $moved++;
            }
            $i++;
        }
        $this->assertTrue(($moved/$max) < 1.01*(1/10));

        // test that minimal movement
        $i=0;
        $max = 2000000;
        $moved = 0;
        while ($i<$max) {
            $shard10 = jchash($i, 25);
            $shard11 = jchash($i, 26);
            if ($shard10 !== $shard11) {
                $moved++;
            }
            $i++;
        }
        $this->assertTrue(($moved/$max) < 1.01*(1/25));
    }

    public function testGetShard()
    {
        $sut = new JumpHashSharding();

        // Only 1 Shard
        $shard = $sut->getShard($this->generateStorageKey(1), 1);
        $this->assertEquals(0, $shard);
        $shard = $sut->getShard($this->generateStorageKey(5), 1);
        $this->assertEquals(0, $shard);

        $shard = $sut->getShard($this->generateStorageKey(1), 5);
        $this->assertEquals(0, $shard);
    }

    protected function generateStorageKey($int)
    {
        $hex = dechex($int);
        $uuid = str_repeat('0', 32 - strlen($hex)) . $hex;
        return new StorageKey($uuid);
    }
}
