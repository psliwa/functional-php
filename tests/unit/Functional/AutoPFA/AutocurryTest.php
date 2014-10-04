<?php


namespace Functional\AutoPFA;

use React\Partial;

class AutocurryTest extends \PHPUnit_Framework_TestCase
{
    const ARITY = 4;

    private $function;

    protected function setUp()
    {
        $this->function = function($a, $b, $c, $d, $e = 5) {
            return func_get_args();
        };
    }

    /**
     * @test
     */
    public function testAutocurry()
    {
        $this->assertEquals(array(1, 2, 3, 4), autocurry($this->function, self::ARITY, array(2, 3, 4, 1)));

        $func1 = autocurry($this->function, self::ARITY, array(2, 3, 4));
        $this->assertEquals(array(1, 2, 3, 4), $func1(1));

        $func2 = autocurry($this->function, self::ARITY, array(2, 3));
        $func2 = $func2(4);
        $this->assertEquals(array(1, 2, 3, 4), $func2(1));

        $func3 = autocurry($this->function, self::ARITY, array(2));
        $func3 = $func3(3);
        $func3 = $func3(4);
        $this->assertEquals(array(1, 2, 3, 4), $func3(1));

        $func3 = autocurry($this->function, self::ARITY, array(2));
        $func3 = $func3(3);
        $func3 = $func3(4);
        $this->assertEquals(array(1, 2, 3, 4), $func3(1));
        $this->assertEquals(array(1, 2, 3, 4, 5), $func3(1, 5));
    }

    /**
     * @test
     * @expectedException \Functional\Exceptions\InvalidArgumentException
     */
    public function givenOnePFA_givenConstraintFOrFirstArg_constraintFails_throwEx()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
        );

        autocurry($this->function, self::ARITY, array(2, 3), $constraints);
    }

    /**
     * @test
     */
    public function givenOnePFA_givenConstraintForFirstArg_constraintSucceeds_shouldBeOk()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
        );

        $func = autocurry($this->function, self::ARITY, array(array(), 3), $constraints);

        $this->assertNotNull($func);
    }

    /**
     * @test
     */
    public function givenTwoTimesPFA_givenConstraintsForTwoArgs_allConstraintsSucceed_shouldBeOk()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
            $this->createCallbackConstraint(1),
        );

        $func = autocurry($this->function, self::ARITY, array(array()), $constraints);
        $func = $func(function(){});

        $this->assertNotNull($func);
    }

    /**
     * @test
     * @expectedException \Functional\Exceptions\InvalidArgumentException
     */
    public function givenTwoTimesPFA_givenConstraintsForTwoArgs_secondConstraintFails_throwEx()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
            $this->createCallbackConstraint(1),
        );

        $func = autocurry($this->function, self::ARITY, array(array()), $constraints);
        $func(array('no-callable', null));
    }

    /**
     * @test
     */
    public function givenThreeTimesPFA_constraintForSecondArgIsMissing_allConstraintsSucceed_shouldBeOk()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
            2 => $this->createCallbackConstraint(2),
        );

        $func = autocurry($this->function, self::ARITY, array(array()), $constraints);
        $func = $func(2);
        $func = $func(function(){});

        $this->assertNotNull($func);
    }

    /**
     * @test
     * @expectedException \Functional\Exceptions\InvalidArgumentException
     */
    public function givenThreeTimesPFA_constraintForSecondArgIsMissing_thirdConstraintFails_throwEx()
    {
        $constraints = array(
            $this->createCollectionConstraint(0),
            2 => $this->createCallbackConstraint(2),
        );

        $func = autocurry($this->function, self::ARITY, array(array()), $constraints);
        $func = $func(2);
        $func = $func(array('no-callable', null));

        $this->assertNotNull($func);
    }

    private function createCollectionConstraint($position)
    {
        return Partial\bind(
            array('Functional\Exceptions\InvalidArgumentException', 'assertCollection'),
            Partial\placeholder(),
            'function',
            $position
        );
    }

    private function createCallbackConstraint($position)
    {
        return Partial\bind(
            array('Functional\Exceptions\InvalidArgumentException', 'assertCallback'),
            Partial\placeholder(),
            'function',
            $position
        );
    }
}