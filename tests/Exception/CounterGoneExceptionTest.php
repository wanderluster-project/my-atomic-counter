<?php

namespace MyAtomic\Tests\Exception;

use MyAtomic\Exception\CounterException;
use MyAtomic\Exception\CounterGoneException;
use PHPUnit\Framework\TestCase;

class CounterGoneExceptionTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new CounterGoneException();
        $this->assertInstanceOf(CounterException::class, $sut);
    }
}
