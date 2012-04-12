<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
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

class AccessTest extends AbstractTestCase
{
    public function testTryCallingMethods()
    {
        $this->assertSame('something', access(new TestClass(), function($object) {
            return $object->getSomething();
        }));
        $this->assertSame('something', access(new TestClass(), function($object) {
            return $object->getThis()->getThis()->getSomething();
        }));
        $this->assertNull(access(new TestClass(), function($object) {
            return $object->undefinedMethod();
        }));
        $this->assertSame('virtualMethod', access(new TestClassWithCall(), function($object) {
            return $object->virtualMethod();
        }));
    }

    public function testTryAccessingProperties()
    {
        $this->assertSame('PUBLIC', access(new TestClass(), function($object) {
            return $object->publicProperty;
        }));
        $this->assertNull(access(new TestClass(), function($object) {
            return $object->protectedProperty;
        }));
        $this->assertNull(access(new TestClass(), function($object) {
            return $object->privateProperty;
        }));
        $this->assertNull(access(new TestClass(), function($object) {
            return $object->undefinedProperty;
        }));
        $this->assertSame('VIRTUAL', access(new TestClassWithGet(), function($object) {
            return $object->virtualProperty;
        }));
        $this->assertSame('VIRTUAL', access(new TestClassWithGet(), function($object) {
            return $object->otherVirtualProperty;
        }));
        $this->assertSame('VIRTUAL', access(new TestClassWithIsset(), function($object) {
            return $object->virtualProperty;
        }));
        $this->assertNull(access(new TestClassWithIsset(), function($object) {
            return $object->otherVirtualProperty;
        }));
        $this->assertNull(access(new TestClassWithProtectedGet(), function($object) {
            return $object->virtualProperty;
        }));
        $this->assertNull(access(new TestClassWithProtectedIsset(), function($object) {
            return $object->virtualProperty;
        }));
    }

    public function testAccessingWithDefault()
    {
        $this->assertSame('DEFAULT', access(new TestClass(), function($object) {
            return $object->protectedProperty;
        }, 'DEFAULT'));
        $this->assertSame('DEFAULT', access(new TestClass(), function($object) {
            return $object->undefinedMethod();
        }, 'DEFAULT'));
    }

    public function testDoingSomethingStupidInsideTheClosure()
    {
        $this->setExpectedException(
            'Functional\Exceptions\AccessViolationException',
            'Closure did not access guarded object'
        );
        access(new TestClass(), function($object) {
            return 'foo';
        });
    }

    public static function provideNonObjectValues()
    {
        return array(
            array(array()),
            array(1),
            array(1.2),
            array(true),
            array(false),
            array('string'),
        );
    }

    /** @dataProvider provideNonObjectValues */
    public function testNonObjectValuesReturnNull($value)
    {
        $this->assertNull(access($value, function($value) {
            return $value->undefinedMethod();
        }));
    }
}

class TestClass
{
    public $publicProperty = 'PUBLIC';
    protected $protectedProperty = 'PROTECTED';
    private $privateProperty = 'PRIVATE';

    public function getSomething()
    {
        return 'something';
    }

    public function getThis()
    {
        return $this;
    }
}

class TestClassWithGet extends TestClass
{
    public function __get($property)
    {
        return 'VIRTUAL';
    }
}

class TestClassWithIsset extends TestClassWithGet
{
    public function __isset($property)
    {
        return $property === 'virtualProperty';
    }
}

class TestClassWithCall extends TestClass
{
    public function __call($method, $arguments)
    {
        return $method;
    }
}

class TestClassWithProtectedGet
{
    protected function __get($property)
    {
        return 'protectedGet';
    }
}

class TestClassWithProtectedIsset
{
    protected function __isset($property)
    {
        return true;
    }

    public function __get($property)
    {
        return 'protectedIsset';
    }
}
