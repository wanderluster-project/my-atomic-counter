<?php

namespace MyAtomic\Tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use MyAtomic\Storage\Conn;

class ConnTest extends TestCase
{
    /**
     * @var Conn
     */
    protected $sut;

    public function setUp():void
    {
        $this->sut = new Conn('fooHost', 3306, 'fooUsername', 'fooPassword', 'fooDB', 'fooCharset');
        parent::setUp();
    }

    public function testSignature()
    {
        $this->assertEquals(md5('fooHost:3306:fooDB'), $this->sut->getSignature());
    }
}
