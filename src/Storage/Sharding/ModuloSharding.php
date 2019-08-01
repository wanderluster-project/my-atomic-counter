<?php

namespace MyAtomic\Storage\Sharding;

use MyAtomic\Storage\StorageKey;

class ModuloSharding implements ShardingStategyInterface
{
    /**
     * @inheritdoc
     */
    public function getShard(StorageKey $key, int $numberOfShards): int
    {
        return ($key->getInt() % $numberOfShards);
    }
}
