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
        $this->sut = new Conn(1, 32, 'fooHost', 3306, 'fooUsername', 'fooPassword', 'fooDB', 'fooCharset');
        parent::setUp();
    }

    public function testCanHandle()
    {
        $this->assertTrue($this->sut->canHandle(1));
        $this->assertTrue($this->sut->canHandle(16));
        $this->assertTrue($this->sut->canHandle(32));
        $this->assertFalse($this->sut->canHandle(0));
        $this->assertFalse($this->sut->canHandle(33));
    }

    public function testSignature()
    {
        $this->assertEquals(md5('1:32:fooHost:3306:fooDB'), $this->sut->getSignature());
    }
}
