<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;
use MyAtomic\Storage\Sharding\ShardingStategyInterface;
use Exception;

class Sharder
{
    /**
     * @var Conn[]
     */
    protected $pool;

    /**
     * @var ShardingStategyInterface
     */
    protected $shardingStrategy;

    /**
     * Sharder constructor.
     * @param ShardingStategyInterface $shardingStategy
     * @param array $connections
     * @throws Exception
     */
    public function __construct(ShardingStategyInterface $shardingStategy, array $connections)
    {
        $this->shardingStrategy = $shardingStategy;

        foreach ($connections as $conn) {
            if (!$conn instanceof Conn) {
                throw new StorageException('Invalid Connection');
            }
            $this->pool[]=$conn;
        }
    }

    public function getShard(StorageKey $storageKey):int
    {
        return $this->shardingStrategy->getShard($storageKey, $this->getNumberOfShards());
    }

    /**
     * Get a connection based on signature
     * @param $signature
     * @return Conn
     */
    public function getConnection(StorageKey $storageKey): Conn
    {
        $shard = $this->getShard($storageKey);

        if (!isset($this->pool[$shard])) {
            throw new StorageException(sprintf('Unable to load shard - %s', $shard));
        }

        return $this->pool[$shard];
    }

    /**
     * @return int
     */
    public function getNumberOfShards():int
    {
        return count($this->pool);
    }
}
