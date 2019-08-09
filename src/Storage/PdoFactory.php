<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;

class PdoFactory
{
    protected $configs = [];

    protected $pool = [];

    /**
     * Conn constructor.
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $charset
     */
    public function addConnection(string $host, int $port, string $username, string $password, string $dbname, string $charset = 'UTF8')
    {
        $signature = $this->getSignature($host, $port, $dbname);
        $this->configs[$signature] = [
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'dbname' => $dbname,
            'charset' => $charset
        ];
    }

    /**
     * Get unique signature of the connection
     * @return string
     */
    public function getSignature($host, $port, $dbName): string
    {
        return md5(
            (string)$host . ':'
            . (string)$port . ':'
            . (string)$dbName
        );
    }

    /**
     * @param $signature
     * @return array
     */
    public function getConfig($signature): array
    {
        if (!array_key_exists($signature, $this->configs)) {
            throw new StorageException('Unable to get PDO configuration.');
        }

        return $this->configs[$signature];
    }

    public function getPDO($signature)
    {
        if (array_key_exists($signature, $this->pool)) {
            return $this->pool[$signature];
        }

        $config = $this->getConfig($signature);
        $host = $config['host'];
        $port = $config['port'];
        $username = $config['username'];
        $password = $config['password'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];

        $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . ';charset=' . $charset;
        try {
            $pdo = new \PDO($dsn, $username, $password);
        } catch (\Exception $e) {
            throw new StorageException(sprintf('Unable to connect to %s:%s %s', $host, $port, $dbname));
        }

        return $pdo;
    }
}