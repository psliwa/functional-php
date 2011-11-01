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
 * Iterate over numeric ranges until a upperBound bound is reached
 */
class RangeIterator implements Iterator
{
    private $leftBound;

    private $rightBound;

    private $rightBoundRange;

    private $increment;

    private $current;

    private $position = 0;

    private $tolerance = 0;

    private $isIncreasing;

    private $isPositive;

    private $isFloat;

    public function __construct($leftBound, $rightBound, $increment = 1, $tolerance = 0.0000000001)
    {
        InvalidArgumentException::assertNumeric($leftBound, __METHOD__, 1);
        InvalidArgumentException::assertNumeric($rightBound, __METHOD__, 2);
        InvalidArgumentException::assertNumeric($increment, __METHOD__, 3);
        InvalidArgumentException::assertNumeric($tolerance, __METHOD__, 4);

        $isIncreasing = $leftBound + $increment > $leftBound;
        $isPositive = $leftBound < $rightBound;

        if ($isPositive && $increment <= 0 || !$isPositive && $increment >= 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Right bound %s is unreachable by incrementing left bound %s by %s',
                    $rightBound,
                    $leftBound,
                    $increment
                )
            );
        }

        $this->isIncreasing = $isIncreasing;
        $this->isPositive = $isPositive;

        $this->isFloat = is_float($leftBound) || is_float($rightBound) || is_float($increment);
        $type = $this->isFloat ? 'float' : 'integer';
        settype($leftBound, $type);
        settype($rightBound, $type);
        settype($increment, $type);

        $this->leftBound  = $leftBound;
        $this->rightBound = $rightBound;
        $this->increment  = $increment;

        if ($this->isFloat) {
            settype($tolerance, 'float');
            $this->tolerance  = $tolerance;
            $this->rightBoundRange = array(
                $this->rightBound + $tolerance,
                $this->rightBound - $tolerance
            );
        } else{
            $this->rightBoundRange = array($this->rightBound);
        }
    }

    public function getLeftBound()
    {
        return $this->leftBound;
    }

    public function getRightBound()
    {
        return $this->rightBound;
    }

    public function getIncrement()
    {
        return $this->increment;
    }

    public function getTolerance()
    {
        return $this->tolerance;
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
        $this->current = $this->leftBound;
    }

    public function next()
    {
        if ($this->isIncreasing ^ $this->isPositive) {
            $this->current -= $this->increment;
        } else {
            $this->current += $this->increment;
        }

        ++$this->position;
    }

    public function valid()
    {
        foreach ($this->rightBoundRange as $rightBound) {
            if ($this->isIncreasing && $this->current <= $rightBound
            || !$this->isIncreasing && $this->current >= $rightBound) {

                return true;
            }
        }

        return false;
    }
}
