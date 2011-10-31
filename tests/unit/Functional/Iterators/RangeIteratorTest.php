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

class RangeIteratorTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        if (!class_exists('Functional\Iterators\RangeIterator')) {
            $this->markTestSkipped('Functional\Iterators\RangeIterator not implemented');
        }
    }

    function testIntRange()
    {
        $this->assertSame(array(0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100), iterator_to_array(new RangeIterator(0, 100, 10)));
        $this->assertSame(array(0, 10, 20, 30, 40, 50, 60, 70, 80, 90), iterator_to_array(new RangeIterator(0, 99, 10)));
        $this->assertSame(array(-10, -20, -30), iterator_to_array(new RangeIterator(-10, -30, -10)));
        $this->assertSame(array(-10, 0, 10), iterator_to_array(new RangeIterator(-10, 10, 10)));
        $this->assertSame(array(10, 0, -10), iterator_to_array(new RangeIterator(10, -10, -10)));
    }

    function testFloatRange()
    {
        $this->assertEquals(array(0, 0.1, 0.2, 0.3, 0.4, 0.5), iterator_to_array(new RangeIterator(0, 0.5, 0.1)), null, 0.00001);
        $this->assertEquals(array(1.1, 2.2, 3.3, 4.4, 5.5), iterator_to_array(new RangeIterator(1.1, 6, 1.1)), null, 0.00001);
        $this->assertEquals(array(10.1, 9.2, 8.3, 7.4, 6.5), iterator_to_array(new RangeIterator(10.1, 6.5, -0.9)), null, 0.000001);
    }

    function testDefaultStepIsOne()
    {
        $this->assertSame(range(0, 100), iterator_to_array(new RangeIterator(0, 100)));
    }

    function testDefaultToleranceIs0dot000000001()
    {
        $it = new RangeIterator(1, 10, 1.0);
        $this->assertSame(0.0000000001, $it->getTolerance());
    }

    function testNoToleranceForIntRanges()
    {
        $it = new RangeIterator(1, 10, 2);
        $this->assertSame(0, $it->getTolerance());
    }

    function testAccessors()
    {
        $it = new RangeIterator(1, 10, 2);
        $this->assertSame(1, $it->getLeftBound());
        $this->assertSame(10, $it->getRightBound());
        $this->assertSame(2, $it->getStep());

        $it = new RangeIterator(1.0, 10, 2);
        $this->assertSame(1.0, $it->getLeftBound());
        $this->assertSame(10.0, $it->getRightBound());
        $this->assertSame(2.0, $it->getStep());
    }

    function testToleranceIsCastedToFloat()
    {
        $it = new RangeIterator(1, 2, 1.0, 1);
        $this->assertSame(1.0, $it->getTolerance());
    }
}
