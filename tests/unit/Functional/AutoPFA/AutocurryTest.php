<?php


namespace Functional\AutoPFA;

class AutocurryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testAutocurry()
    {
        $function = function($a = null, $b = null, $c = null, $d = null) {
            return func_get_args();
        };

        $this->assertEquals(array(1, 2, 3, 4), autocurry($function, 4, array(2, 3, 4, 1)));

        $func1 = autocurry($function, 4, array(2, 3, 4));
        $this->assertEquals(array(1, 2, 3, 4), $func1(1));

        $func2 = autocurry($function, 4, array(2, 3));
        $func2 = $func2(4);
        $this->assertEquals(array(1, 2, 3, 4), $func2(1));

        $func3 = autocurry($function, 4, array(2));
        $func3 = $func3(3);
        $func3 = $func3(4);
        $this->assertEquals(array(1, 2, 3, 4), $func3(1));

        $func3 = autocurry($function, 4, array(2));
        $func3 = $func3(3);
        $func3 = $func3(4);
        $this->assertEquals(array(1, 2, 3, 4), $func3(1));
    }
} 