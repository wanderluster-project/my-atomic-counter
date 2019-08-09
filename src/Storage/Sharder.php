<?php

namespace MyAtomic\Storage;

use MyAtomic\Storage\ShardingStrategy\ShardingStategyInterface;
use Exception;

class Sharder
{
    /**
     * @var int
     */
    protected $numberOfShards=0;

    /**
     * @var ShardingStategyInterface
     */
    protected $shardingStrategy;

    /**
     * Sharder constructor.
     * @param ShardingStategyInterface $shardingStategy
     * @param int $numberOfShards
     */
    public function __construct(ShardingStategyInterface $shardingStategy, int $numberOfShards)
    {
        $this->shardingStrategy = $shardingStategy;
        $this->numberOfShards = $numberOfShards;
    }

    /**
     * @param StorageKey $storageKey
     * @return int
     */
    public function getShard(StorageKey $storageKey):int
    {
        return $this->shardingStrategy->getShard($storageKey, $this->getNumberOfShards());
    }

    /**
     * @return int
     */
    public function getNumberOfShards():int
    {
        return $this->numberOfShards;
    }
}
