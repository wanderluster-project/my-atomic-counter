<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;

class ShardCollection
{
    /**
     * @var Shard[]
     */
    protected $pool = [];

    /**
     * ShardCollection constructor.
     * @param Shard[] $conn
     */
    public function __construct(array $conn){
        foreach($conn as $item){
            if(!$conn instanceof Shard){
                Throw new StorageException('Invalid argument for ShardCollection');
            }
            $this->pool[count($this->pool)] = $item;
        }
    }

    /**
     * @param int $shard
     * @return Shard
     */
    public function getShard(int $shard){
        if(!array_key_exists($shard, $this->pool)){
            throw new StorageException('Invalid shard - %s',$shard);
        }
        return $this->pool[$shard];
    }

    /**
     * @return int
     */
    public function getNumberOfShards(){
        return count($this->pool);
    }
}