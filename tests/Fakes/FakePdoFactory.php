<?php

namespace MyAtomic\Tests\Fakes;

use MyAtomic\Storage\PdoFactory;

class FakePdoFactory extends PdoFactory
{
    public function getPDO($signature)
    {
        $config = $this->getConfig($signature);
        $host = $config['host'];
        $port = $config['port'];
        $username = $config['username'];
        $password = $config['password'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];

        $dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . ';port=' . $port . ';charset=' . $charset;
        return new FakePDO($dsn, $username, $password);
    }

}