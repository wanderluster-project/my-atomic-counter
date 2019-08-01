<?php

namespace MyAtomic\Tests\Unit\Storage;

use MyAtomic\Storage\Conn;
use MyAtomic\Storage\Storage;
use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase
{
    /**
     * @var Storage
     */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Storage();
    }

    public function testAddGetConnection()
    {
        $conn = new Conn('fooHost', 3306, 'fooUsername', 'fooPassword', 'fooDb');
        $signature = $conn->getSignature();
        $this->sut->addConnection($conn);
        $this->assertEquals($conn, $this->sut->getConnection($signature));
    }
}
