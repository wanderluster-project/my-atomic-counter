<?php

namespace MyAtomic\Storage\ShardingStrategy;

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
