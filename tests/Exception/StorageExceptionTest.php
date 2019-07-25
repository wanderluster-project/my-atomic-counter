<?php

namespace MyAtomic\Tests\Exception;

use MyAtomic\Exception\CounterException;
use MyAtomic\Exception\StorageException;
use PHPUnit\Framework\TestCase;

class StorageExceptionTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new StorageException();
        $this->assertInstanceOf(CounterException::class, $sut);
    }
}
