--TEST--
Class aliasing
--FILE--
<?php

use \DateTime as aliasDateTime;

$a = new DateTime();
$b = new aliasDateTime();

return [
    \get_class( $a ),
    \get_class( $b )
];

--EXPECT--
["DateTime", "DateTime"]
