<?php

namespace MyAtomic\Storage\Sharding;

use MyAtomic\Storage\StorageKey;

class UnarySharding implements ShardingStategyInterface
{
    /**
     * @inheritdoc
     */
    public function getShard(StorageKey $key, int $numberOfShards): int
    {
        return 1;
    }
}
