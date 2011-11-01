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

class LimitIteratorTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        if (!class_exists('Functional\Iterators\LimitIterator')) {
            $this->markTestSkipped('Functional\Iterators\LimitIterator not implemented');
        }

        $this->list = array('one', 'two', 'three', 'four');
        $this->listIt = new ArrayIterator($this->list);
        $this->map = array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4);
        $this->mapIt = new ArrayIterator($this->map);
    }

    function testLimitingArray()
    {
        $this->assertSame(array('one', 'two'), iterator_to_array(new LimitIterator($this->list, 0, 2)));
        $this->assertSame(array(1 => 'two', 2 => 'three'), iterator_to_array(new LimitIterator($this->list, 1, 2)));
        $this->assertSame(array('one' => 1, 'two' => 2), iterator_to_array(new LimitIterator($this->map, 0, 2)));
        $this->assertSame(array('two' => 2, 'three' => 3), iterator_to_array(new LimitIterator($this->map, 1, 2)));
    }

    function testLimitingIterator()
    {
        $this->assertSame(array('one', 'two'), iterator_to_array(new LimitIterator($this->listIt, 0, 2)));
        $this->assertSame(array(1 => 'two', 2 => 'three'), iterator_to_array(new LimitIterator($this->listIt, 1, 2)));
        $this->assertSame(array('one' => 1, 'two' => 2), iterator_to_array(new LimitIterator($this->mapIt, 0, 2)));
        $this->assertSame(array('two' => 2, 'three' => 3), iterator_to_array(new LimitIterator($this->mapIt, 1, 2)));
    }

    function testExceptionIsThrownIfInvalidLimitIsGiven()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Functional\Iterators\LimitIterator::__construct() expects parameter 3 to be positive integer, negative integer given');
        new LimitIterator(array(), 0, -1);
    }

    function testExceptionIsThrownIfInvalidOffsetIsGiven()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Functional\Iterators\LimitIterator::__construct() expects parameter 2 to be positive integer, negative integer given');
        new LimitIterator(array(), -1, 1);
    }

    function testExceptionIsThrownIfInvalidCollectionIsGiven()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException', 'Functional\Iterators\LimitIterator::__construct() expects parameter 1 to be array or instance of Traversable');
        new LimitIterator("invalidValue", 0, 1);
    }
}
