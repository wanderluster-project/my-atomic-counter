<?php

namespace MyAtomic\Storage\Sharding;

use MyAtomic\Storage\StorageKey;

interface ShardingStategyInterface
{
    /**
     * Calculate shard # for a given key.
     * Returns back an integer between [ 0, $numberOfShards - 1 ]
     * @param StorageKey $key
     * @param int $numberOfShards
     * @return int
     */
    public function getShard(StorageKey $key, int $numberOfShards):int;
}
