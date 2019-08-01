<?php

namespace MyAtomic\Storage\Sharding;

interface ShardingStategyInterface
{
    public function getShard($key, $numberOfShards);
}
