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

use Iterator,
    Functional\Exceptions\InvalidArgumentException;

/**
 * Infinite iterator to increment a start value by a given increment value
 */
class IncrementIterator implements Iterator
{
    /**
     * Start value
     *
     * @var integer|float
     */
    private $start;

    /**
     * Number to increment start value
     *
     * @var integer|float
     */
    private $increment;

    /**
     * Current value (during iteration)
     *
     * @var integer|float
     */
    private $current;

    /**
     * Current position (during iteration)
     *
     * @var integer
     */
    private $position = 0;

    /**
     * Create new IncrementIterator instance
     *
     * @param integer|float $start Start value from where to start incrementing
     * @param integer|float $increment Increment value
     */
    public function __construct($start, $increment = 1)
    {
        InvalidArgumentException::assertNumeric($start, __METHOD__, 1);
        InvalidArgumentException::assertNumeric($increment, __METHOD__, 2);

        /** Small trick to cast both values to appropriate numeric types */
        $start += $increment - $increment;
        $increment += $start - $start;

        $this->start     = $start;
        $this->increment = $increment;
    }

    /**
     * Return start value
     *
     * @return integer|float
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Return increment value
     *
     * @return integer|float
     */
    public function getIncrement()
    {
        return $this->increment;
    }

    /**
     * Return current value
     *
     * @return integer|float
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Return current key
     *
     * @return integer
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Rewind iterator
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
        $this->current = $this->start;
    }

    /**
     * Set iterator to the next position
     *
     * @return void
     */
    public function next()
    {
        $this->current += $this->increment;

        ++$this->position;
    }

    /**
     * Returns true if iterator will return a value
     *
     * As IncrementIterator is an infinite iterator, this method will always
     * return true.
     *
     * @return bool
     */
    public function valid()
    {
        return true;
    }
}
