<?php

namespace MyAtomic\Tests\Fakes;

class FakePDO
{
    protected $dsn;
    protected $username;
    protected $password;

    public function __construct($dsn, $username, $password)
    {
        $this->dsn = $dsn;
        $this->username=$username;
        $this->password=$password;
    }
}