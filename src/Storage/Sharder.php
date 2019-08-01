<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;

class Sharder
{
    /**
     * @var Conn[]
     */
    protected $pool;

    /**
     * Add a connection to the pool
     *
     * @param Conn $conn
     * @return Void
     */
    public function addConnection(Conn $conn):Void
    {
        $index = count($this->pool);
        $this->pool[$conn->getSignature()] = ['index'=> $index, 'conn'=>$conn];
    }

    /**
     * Get a connection based on signature
     * @param $signature
     * @return Conn
     */
    public function getConnection($signature): Conn
    {
        if (!array_key_exists($signature, $this->pool)) {
            throw new StorageException(sprintf('Connection signature does not exist - %s', $signature));
        }

        return $this->pool[$signature];
    }

    /**
     * @return int
     */
    public function getNumberOfShards():int
    {
        return count($this->pool);
    }

    /**
     * @param int $shard
     * @return Conn
     */
    public function getConnectionForShard(int $shard):Conn
    {
        if (!is_int($shard)) {
            throw new StorageException(sprintf('Invalid shard provided.', $shard));
        }

        if ($shard >= $this->getNumberOfShards()) {
            throw new StorageException(sprintf('Shard # provided %s is greater than the number of shards', $shard));
        }

        if ($shard < 0) {
            throw new StorageException(sprintf('Shard # provided %s is less than zero.', $shard));
        }

        foreach ($this->pool as $shardItem) {
            if ($shardItem['index'] == $shard) {
                return $shardItem['conn'];
            }
        }
    }

    public function getShardForKey($key)
    {
        $hash = $this->getHashForKey($key);
        return 1;
    }
}
