<?php
/**
 * Copyright (C) 2011 by David Soria Parra <dsp@php.net>
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

use Iterator;

/**
 * Iterate over numeric ranges until the right bound is reached
 */
class RangeIterator implements Iterator
{
    private $left;

    private $right;

    private $step;

    private $current;

    private $position = 0;

    private $tolerance = 0.00000000000001;

    private $isIncreasing;

    private $isPositive;

    public function __construct($left, $right, $step = 1, $tolerance = 0.00000000000001)
    {
        $this->left = $left;
        $this->right = $right;
        $this->step = $step;

        $this->isIncreasing = $right + $step > $right;
        $this->isPositive = $left < $right;

        $this->tolerance = $tolerance;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->position;
    }

    public function rewind()
    {
        $this->position = 0;
        $this->current = $this->left;
    }

    public function next()
    {
        if (($this->isIncreasing && $this->isPositive) || (!$this->isIncreasing && !$this->isPositive)) {
            $this->current += $this->step;
        } else {
            $this->current -= $this->step;
        }
        ++$this->position;
    }

    public function valid()
    {
        if ($this->isIncreasing && $this->isPositive) {
            return $this->current <= $this->right + $this->tolerance
                || $this->current <= $this->right - $this->tolerance;
        } else {
            return $this->current >= $this->right + $this->tolerance
                || $this->current >= $this->right - $this->tolerance;
        }
    }
}
