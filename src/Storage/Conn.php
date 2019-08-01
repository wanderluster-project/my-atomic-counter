<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;
use PDO;

class Conn
{

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
     * @var PDO
     */
    protected $pdo;

    /**
     * Conn constructor.
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $charset
     */
    public function __construct(string $host, int $port, string $username, string $password, string $dbname, string $charset='UTF8')
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;
    }

    /**
     * Get unique signature of the connection
     * @return string
     */
    public function getSignature():string
    {
        return md5(
            (string)$this->host.':'
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
        if ($this->pdo) {
            return $this->pdo;
        }

        $dsn = 'mysql:dbname='.$this->dbname.';host='.$this->host.';charset='.$this->charset;
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
        } catch (\Exception $e) {
            throw new StorageException(sprintf('Unable to connect to %s:%s %s', $this->host, $this->port, $this->dbname));
        }

        return $this->pdo;
    }
}
