<?php

namespace MyAtomic\Tests\Unit\Exception;

use MyAtomic\Exception\CounterException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CounterExceptionTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new CounterException();
        $this->assertInstanceOf(RuntimeException::class, $sut);
    }
}
