<?php

namespace MyAtomic\Tests\Exception;

use MyAtomic\Exception\ConditionFailsException;
use MyAtomic\Exception\CounterException;
use PHPUnit\Framework\TestCase;

class ConditionFailsExceptionTest extends TestCase
{
    public function testInterfaces()
    {
        $sut = new ConditionFailsException();
        $this->assertInstanceOf(CounterException::class, $sut);
    }
}
