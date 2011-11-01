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

class RepeatTest extends AbstractTestCase
{
    function testPassingSequenceOfValues()
    {
        $n = 0;
        foreach (repeat('one', 'two', 'three') as $k => $v) {
            $this->assertContains($v, array('one', 'two', 'three'));

            if (++$n === 100) {
                break;
            }
        }
    }

    function testPassingArrayOfValues()
    {
        $n = 0;
        foreach (repeat(array('one', 'two', 'three')) as $k => $v) {
            $this->assertContains($v, array('one', 'two', 'three'));
            if (++$n == 100) {
                break;
            }
        }
    }

    function testPassingIteratorOfValues()
    {
        $n = 0;
        foreach (repeat(new ArrayIterator(array('one', 'two', 'three'))) as $k => $v) {
            $this->assertContains($v, array('one', 'two', 'three'));
            if (++$n == 100) {
                break;
            }
        }
    }

    function testPassingIteratorOfKeyedValues()
    {
        $n = 0;
        foreach (repeat(new ArrayIterator(array('one' => 1, 'two' => 2, 'three' => 3))) as $k => $v) {
            $this->assertContains($k, array('one', 'two', 'three'));
            $this->assertContains($v, array(1, 2, 3));
            if (++$n === 100) {
                break;
            }
        }
    }

    function testPassingArrayOfKeyedValues()
    {
        $n = 0;
        foreach (repeat(array('one' => 1, 'two' => 2, 'three' => 3)) as $k => $v) {
            $this->assertContains($k, array('one', 'two', 'three'));
            $this->assertContains($v, array(1, 2, 3));
            if (++$n === 100) {
                break;
            }
        }
    }
}
