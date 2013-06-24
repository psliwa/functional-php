<?php
/**
 * Copyright (C) 2011-2013 by Lars Strojny <lstrojny@php.net>
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

class ReindexTest extends AbstractTestCase
{
    private $currentCollection;

    function setUp()
    {
        parent::setUp();
        $this->array = array('value1', 'value2', 'value3');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $this->currentCollection = $this->array;
        $this->assertSame(
            array('value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'),
            reindex($this->array,array($this, 'reindexer'))
        );
        $this->currentCollection = $this->iterator;
        $this->assertSame(
            array('value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'),
            reindex($this->iterator, array($this, 'reindexer'))
        );
        $this->currentCollection = $this->hash;
        $this->assertSame(
            array('val1' => 'val1', 'val2' => 'val2', 'val3' => 'val3'),
            reindex($this->hash, array($this, 'reindexer'))
        );
        $this->currentCollection = $this->hashIterator;
        $this->assertSame(
            array('val1' => 'val1', 'val2' => 'val2', 'val3' => 'val3'),
            reindex($this->hashIterator, array($this, 'reindexer'))
        );
    }

    public function reindexer($value, $key, $collection)
    {
        $this->assertSame($this->currentCollection, $collection);

        return $value;
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reindex($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reindex($this->hash, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reindex($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        reindex($this->hashIterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\reindex() expects parameter 1 to be array or instance of Traversable');
        reindex('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\\reindex() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        reindex($this->array, 'undefinedFunction');
    }
}
