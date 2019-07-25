<?php

namespace MyAtomic\Tests\Exception;

use MyAtomic\Exception\CounterException;
use MyAtomic\Exception\TimeoutException;
use PHPUnit\Framework\TestCase;

class TimeoutExceptionTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new TimeoutException();
        $this->assertInstanceOf(CounterException::class, $sut);
    }
}
