<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;

class Storage
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
        $this->pool[$conn->getSignature()] = $conn;
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
}
