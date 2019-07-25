<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;
use PDO;

class Conn
{
    /**
     * @var int
     */
    protected $minShard;

    /**
     * @var int
     */
    protected $maxShard;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $dbname;

    /**
     * @var string
     */
    protected $charset;

    /**
     * Conn constructor.
     * @param int $minShard
     * @param int $maxShard
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $charset
     */
    public function __construct(int $minShard, int $maxShard, string $host, int $port, string $username, string $password, string $dbname, string $charset='UTF8')
    {
        $this->minShard = $minShard;
        $this->maxShard = $maxShard;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;
    }

    /**
     * Returns true if connection can handle a given shard.
     * @param $shard
     * @return bool
     */
    public function canHandle($shard):bool
    {
        return $shard >= $this->minShard && $shard <= $this->maxShard;
    }

    /**
     * Get unique signature of the connection
     * @return string
     */
    public function getSignature():string
    {
        return md5(
            (string)$this->minShard.':'
            .(string)$this->maxShard.':'
            .(string)$this->host.':'
            .(string)$this->port.':'
            .(string)$this->dbname
        );
    }

    /**
     * Build PDO object for querying.
     * @return PDO
     */
    public function getPdo():PDO
    {
        $dsn = 'mysql:dbname='.$this->dbname.';host='.$this->host.';charset='.$this->charset;
        try {
            $pdo = new PDO($dsn, $this->username, $this->password);
        } catch (\Exception $e) {
            throw new StorageException(sprintf('Unable to connect to %s:%s %s', $this->host, $this->port, $this->dbname));
        }
        return $pdo;
    }
}
