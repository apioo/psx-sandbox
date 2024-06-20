--TEST--
Test use with alias NOT allowed class
--FILE--
<?php

use PSX\Sandbox\Tests\FooService as FooService1;

new FooService1();

return 1;

--EXPECT--
1
