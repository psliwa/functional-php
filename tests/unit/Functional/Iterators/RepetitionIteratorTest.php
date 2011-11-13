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

class RepetitionIteratorTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        if (!class_exists('Functional\Iterators\RepetitionIterator')) {
            $this->markTestSkipped('Functional\Iterators\RepetitionIterator not implemented');
        }

        $this->keyIteratorFromIterator = new RepetitionIterator(new ArrayIterator(array('A' => 'a', 'B' => 'b', 'C' => 'c')));
        $this->keyIteratorFromArray = new RepetitionIterator(array('A' => 'a', 'B' => 'b', 'C' => 'c'));
        $this->iteratorFromIterator = new RepetitionIterator(new ArrayIterator(array('a', 'b', 'c')));
        $this->iteratorFromArray = new RepetitionIterator(array('a', 'b', 'c'));
    }

    function testIteratorReturnsThousandTimesTheSame()
    {
        $this->assertIteratorRepetition($this->iteratorFromArray, array('a', 'b', 'c'), array(0, 1, 2));
        $this->assertIteratorRepetition($this->iteratorFromIterator, array('a', 'b', 'c'), array(0, 1, 2));
        $this->assertIteratorRepetition($this->keyIteratorFromIterator, array('a', 'b', 'c'), array('A', 'B', 'C'));
        $this->assertIteratorRepetition($this->keyIteratorFromArray, array('a', 'b', 'c'), array('A', 'B', 'C'));
    }

    function testInvalidConstructorArgs()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Functional\Iterators\RepetitionIterator::__construct() expects parameter 1 to be array or instance of Traversable'
        );
        new RepetitionIterator("foo");
    }

    function assertIteratorRepetition($iterator, $refValues, $refKeys)
    {
        $num = 0;
        $refCount = count($refValues);
        foreach ($iterator as $key => $value) {
            $num++;
            $refKey = $num % $refCount == 0 ? 3 : $num % $refCount;
            $refKey--;
            $this->assertSame($refValues[$refKey], $value);
            $this->assertSame($refKeys[$refKey], $key);
            if ($num === 1000) {
                break;
            }
        }
    }
}
