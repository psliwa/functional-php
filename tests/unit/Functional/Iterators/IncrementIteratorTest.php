<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Iterators;

use ArrayIterator;

class IncrementIteratorTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        if (!class_exists('Functional\Iterators\IncrementIterator')) {
            $this->markTestSkipped('Functional\Iterators\IncrementIterator not implemented');
        }
    }

    function testIncrementsByOnePerDefault()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(2), 0, 5);
        $this->assertSame(2, $inner->getStart());
        $this->assertSame(1, $inner->getIncrement());
        $this->assertSame(array(2, 3, 4, 5, 6), iterator_to_array($iterator));
    }

    function testIncrementPositiveNumberByNegativeNumber()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(10, -1), 0, 10);
        $this->assertSame(10, $inner->getStart());
        $this->assertSame(-1, $inner->getIncrement());
        $this->assertSame(array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1), iterator_to_array($iterator));
    }

    function testIncrementNegativeNumberByNegativeNumber()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(-10, -1), 0, 10);
        $this->assertSame(-10, $inner->getStart());
        $this->assertSame(-1, $inner->getIncrement());
        $this->assertSame(array(-10, -11, -12, -13, -14, -15, -16, -17, -18, -19), iterator_to_array($iterator));
    }

    function testIncrementNegativeNumberByPositiveNumber()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(-10, 1), 0, 10);
        $this->assertSame(-10, $inner->getStart());
        $this->assertSame(1, $inner->getIncrement());
        $this->assertSame(array(-10, -9, -8, -7, -6, -5, -4, -3, -2, -1), iterator_to_array($iterator));
    }

    function testIncrementingFloatValue()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(-10, 0.1), 0, 5);
        $this->assertInternalType('float', $inner->getStart());
        $this->assertSame(-10.0, $inner->getStart());
        $this->assertInternalType('float', $inner->getIncrement());
        $this->assertSame(0.1, $inner->getIncrement());
        $this->assertEquals(array(-10.0, -9.9, -9.8, -9.7, -9.6), iterator_to_array($iterator), null, 0.0000000001);
        foreach ($iterator as $v) {
            $this->assertInternalType('float', $v);
        }
    }

    function testIncrementingFloatStringValue()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator("-10", 0.1), 0, 5);
        $this->assertInternalType('float', $inner->getStart());
        $this->assertSame(-10.0, $inner->getStart());
        $this->assertInternalType('float', $inner->getIncrement());
        $this->assertSame(0.1, $inner->getIncrement());
        $this->assertEquals(array(-10.0, -9.9, -9.8, -9.7, -9.6), iterator_to_array($iterator), null, 0.0000000001);
        foreach ($iterator as $v) {
            $this->assertInternalType('float', $v);
        }
    }

    function testIncrementingByFloatStringValue()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(-10, "0.1"), 0, 5);
        $this->assertInternalType('float', $inner->getStart());
        $this->assertSame(-10.0, $inner->getStart());
        $this->assertInternalType('float', $inner->getIncrement());
        $this->assertSame(0.1, $inner->getIncrement());
        $this->assertEquals(array(-10.0, -9.9, -9.8, -9.7, -9.6), iterator_to_array($iterator), null, 0.0000000001);
        foreach ($iterator as $v) {
            $this->assertInternalType('float', $v);
        }
    }

    function testIncrementingIntegerStringValue()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator("-10", 1), 0, 5);
        $this->assertSame(-10, $inner->getStart());
        $this->assertSame(1, $inner->getIncrement());
        $this->assertSame(array(-10, -9, -8, -7, -6), iterator_to_array($iterator));
        foreach ($iterator as $v) {
            $this->assertInternalType('integer', $v);
        }
    }

    function testIncrementingByIntegerStringValue()
    {
        $iterator = new LimitIterator($inner = new IncrementIterator(-10, "1"), 0, 5);
        $this->assertSame(-10, $inner->getStart());
        $this->assertSame(1, $inner->getIncrement());
        $this->assertSame(array(-10, -9, -8, -7, -6), iterator_to_array($iterator));
        foreach ($iterator as $v) {
            $this->assertInternalType('integer', $v);
        }
    }

    function testExceptionIsThrownIfNonNumericValueIsGivenForStart()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Functional\Iterators\IncrementIterator::__construct() expects parameter 1 to be numeric (integer, float or numeric string), string given'
        );
        new IncrementIterator('str', 1);
    }

    function testExceptionIsThrownIfNonNumericValueIsGivenForIncrementValue()
    {
        $this->setExpectedException(
            'Functional\Exceptions\InvalidArgumentException',
            'Functional\Iterators\IncrementIterator::__construct() expects parameter 2 to be numeric (integer, float or numeric string), string given'
        );
        new IncrementIterator(1, 'str');
    }
}
