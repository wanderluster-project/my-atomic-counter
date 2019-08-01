<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/26/19
 * Time: 7:32 PM
 */

namespace MyAtomic\Tests\Unit\Storage;

use MyAtomic\Storage\StorageKey;
use PHPUnit\Framework\TestCase;

class StorageKeyTest extends TestCase
{
    public function testGetHashKey()
    {
        $sut = new StorageKey('foo');
        $this->assertEquals('b8fe9f7f6255a6fa08f668ab632a8d08',$sut->getHashKey());
    }

    public function testGetHashKeyUUID(){
        $sut = new StorageKey('07164a90-f955-4a9b-8936-42c09f768305');
        $this->assertEquals('07164a90f9554a9b893642c09f768305', $sut->getHashKey());

        $sut = new StorageKey('{551ed55f-db96-4c76-a377-15552517ab92}');
        $this->assertEquals('551ed55fdb964c76a37715552517ab92', $sut->getHashKey());
    }

    public function testGetInt(){
        $sut = new StorageKey('535f26ae-5e7c-4fcf-9dc1-b52e875c0de2');
        $this->assertEquals(23467042805808207, $sut->getInt());

        $sut = new StorageKey('535f26ae-5e7c-4fcf-9dc1-b52e875c0de2');
        $this->assertEquals(23467042805808207, $sut->getInt());
        $this->assertTrue(is_int($sut->getInt()));

        $sut = new StorageKey(str_repeat('0',32));
        $this->assertEquals(0, $sut->getInt());
        $this->assertTrue(is_int($sut->getInt()));

        $sut = new StorageKey(str_repeat('F',32));
        $this->assertEquals(2**56-1, $sut->getInt());
        $this->assertTrue(is_int($sut->getInt()));
    }
}
