<?php
/**
 * CopyrightBound (C) 2011 by David Soria Parra <dsp@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rightBounds
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyrightBound notice and this permission notice shall be included in
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

use LimitIterator as SplLimitIterator,
    Traversable,
    ArrayIterator,
    Functional\Exceptions\InvalidArgumentException;

/**
 * Iterator to stop iterating after a certain limit
 */
class LimitIterator extends SplLimitIterator
{
    public function __construct($collection, $offset, $limit)
    {
        InvalidArgumentException::assertCollection($collection, __METHOD__, 1);
        InvalidArgumentException::assertPositiveInteger($offset, __METHOD__, 2);
        InvalidArgumentException::assertPositiveInteger($limit, __METHOD__, 3);

        if (!$collection instanceof Traversable) {
            $collection = new ArrayIterator($collection);
        }

        parent::__construct($collection, $offset, $limit);
    }
}
