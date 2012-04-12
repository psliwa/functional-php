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

use ReflectionObject;

function access($object, $closure, $default = null)
{
    if (!is_object($object)) {
        return null;
    }

    $guard = new Accessor($object);
    try {
        $result = $closure($guard);

        if (!$result instanceof Accessor) {
            throw new Exceptions\AccessViolationException('Closure did not access guarded object');
        }

        return $result->object;
    } catch (Exceptions\AccessException $e) {
        return $default;
    }
}

class Accessor
{
    public $object;

    private $reflected;

    public function __construct($object)
    {
        $this->object = $object;
        if (is_object($object)) {
            $this->reflected = new ReflectionObject($object);
        }
    }

    public function __call($method, $arguments)
    {
        if ($this->reflected->hasMethod($method)) {
            if (!$this->reflected->getMethod($method)->isPublic()) {
                throw new Exceptions\AccessException(
                    sprintf('Call to non public method %s::%s()', get_class($this->object), $method)
                );
            }
        } elseif (!$this->reflected->hasMethod('__call')) {
            throw new Exceptions\AccessException(
                sprintf('Call to undefined method %s::%s()', get_class($this->object), $method)
            );
        }

        return new static(call_user_func_array(array($this->object, $method), $arguments));
    }

    public function __get($property)
    {
        if ($this->reflected->hasProperty($property)) {
            if (!$this->reflected->getProperty($property)->isPublic()) {
                throw new Exceptions\AccessException(sprintf('Access of invalid property %s', $property));
            }
        } elseif ($this->reflected->hasMethod('__get')) {
            if (!$this->reflected->getMethod('__get')->isPublic()) {
                throw new Exceptions\AccessException(
                    sprintf('Access of invalid virtual property %s. __get()-method is not public', $property)
                );
            }

            if ($this->reflected->hasMethod('__isset') && (!$this->reflected->getMethod('__isset')->isPublic() || !isset($this->object->$property))) {
                throw new Exceptions\AccessException(
                    sprintf(
                        'Access of invalid virtual property %s. __isset()-method indicated property is not accessable',
                        $property
                    )
                );
            }
        } else {
            throw new Exceptions\AccessException(sprintf('Access of invalid property %s', $property));
        }

        return new static($this->object->{$property});
    }
}