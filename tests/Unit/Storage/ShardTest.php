<?php

namespace MyAtomic\Tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use MyAtomic\Storage\Shard;

class ShardTest extends TestCase
{
    public function testSignature()
    {
        $sut = new Shard('fooHost', 3306, 'fooUsername', 'fooPassword', 'fooDB', 'fooCharset');;
        $this->assertEquals(md5('fooHost:3306:fooDB'), $sut->getSignature());
    }
}
