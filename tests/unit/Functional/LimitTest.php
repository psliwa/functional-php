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
namespace Functional;

use ArrayIterator;

class LimitTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();

        $this->list = array('one', 'two', 'three', 'four');
        $this->listIt = new ArrayIterator($this->list);
        $this->map = array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4);
        $this->mapIt = new ArrayIterator($this->map);
    }

    function testLimitingIterators()
    {
        $limited = limit($this->listIt, 2);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('one', 'two'), iterator_to_array($limited));

        $limited = limit($this->listIt, 2, 1);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array(1 => 'two', 2 => 'three'), iterator_to_array($limited));

        $limited = limit($this->mapIt, 2);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('one' => 1, 'two' => 2), iterator_to_array($limited));

        $limited = limit($this->mapIt, 10, 1);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('two' => 2, 'three' => 3, 'four' => 4), iterator_to_array($limited));
    }

    function testLimitingArrays()
    {
        $limited = limit($this->list, 2);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('one', 'two'), iterator_to_array($limited));

        $limited = limit($this->map, 2);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('one' => 1, 'two' => 2), iterator_to_array($limited));

        $limited = limit($this->list, 3, 1);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array(1 => 'two', 2 => 'three', 3 => 'four'), iterator_to_array($limited));

        $limited = limit($this->map, 5, 1);
        $this->assertInstanceOf('Functional\Iterators\LimitIterator', $limited);
        $this->assertSame(array('two' => 2, 'three' => 3, 'four' => 4), iterator_to_array($limited));
    }
}
