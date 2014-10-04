<?php

namespace Functional\AutoPFA;

function autocurry($func, $arity, array $arguments, array $constraints = array(), $reverseFirstArg = true)
{
    $argumentsCount = count($arguments);

    if($reverseFirstArg)
    {
        $func = function() use($func, $arity){
            $arguments = func_get_args();

            if($arguments) {
                $argument = $arguments[$arity-1];
                unset($arguments[$arity-1]);
                array_unshift($arguments, $argument);
            }

            return call_user_func_array($func, $arguments);
        };
    }

    if($argumentsCount < $arity)
    {
        list($currentCallConstraints, $nextCallConstraints) =
            \Functional\partition(
                $constraints,
                function($_, $position) use($argumentsCount){
                    return $position < $argumentsCount;
                }
            );

        foreach($currentCallConstraints as $at => $constraint) {
            call_user_func($constraint, $arguments[$at]);
        }

        if($nextCallConstraints) {
            $nextCallConstraints = array_combine(
                \Functional\map(
                    $nextCallConstraints,
                    function($_, $position) use($argumentsCount){
                        return $position - $argumentsCount;
                    }
                ),
                array_values($nextCallConstraints)
            );
        }

        array_unshift($arguments, $func);

        $func = call_user_func_array('React\\Partial\\bind', $arguments);
        $arity -= $argumentsCount;

        return function() use($func, $arity, $nextCallConstraints){
            return autocurry($func, $arity, func_get_args(), $nextCallConstraints, false);
        };
    }
    else
    {
        return call_user_func_array($func, $arguments);
    }
}