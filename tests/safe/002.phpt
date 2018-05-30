--TEST--
Return array
--FILE--
<?php
return ['foo' => $foo];

--EXPECT--
{"foo": "bar"}
