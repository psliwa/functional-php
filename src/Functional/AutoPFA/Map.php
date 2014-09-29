<?php

namespace Functional\AutoPFA;

function map($callback = null, $collection = null)
{
    return autocurry('Functional\\map', 2, func_get_args());
}