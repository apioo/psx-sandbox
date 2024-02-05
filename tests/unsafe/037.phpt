--TEST--
Check restrictions aren't bypassed by using named parametes in different order
--FILE--
<?php

$a = [ 1 ];
return array_walk( /*array*/ array: $a, /*callback*/ arg: 'intval', /*arg*/ callback: 'var_dump' );
--EXPECT--
1
