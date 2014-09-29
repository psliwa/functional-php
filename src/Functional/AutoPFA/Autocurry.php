<?php

namespace Functional\AutoPFA;

function autocurry($func, $arity, array $arguments, $reverseFirstArg = true)
{
    $argumentsCount = count($arguments);

    if($reverseFirstArg)
    {
        $func = function() use($func){
            $arguments = func_get_args();

            if($arguments) {
                $argument = array_pop($arguments);
                array_unshift($arguments, $argument);
            }

            return call_user_func_array($func, $arguments);
        };
    }

    if($argumentsCount < $arity)
    {
        array_unshift($arguments, $func);

        $func = call_user_func_array('React\\Partial\\bind', $arguments);

        return function() use($func, $arity, $argumentsCount){
            return autocurry($func, $arity - $argumentsCount, func_get_args(), false);
        };
    }
    else
    {
        return call_user_func_array($func, $arguments);
    }
}