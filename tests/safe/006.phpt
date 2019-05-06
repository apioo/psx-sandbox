--TEST--
simple function class
--FILE--
<?php

function myFunc()
{
    return "bar";
}

return [
    "foo" => myFunc(),
];

--EXPECT--
{"foo": "bar"}
