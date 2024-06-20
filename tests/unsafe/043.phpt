--TEST--
Test use with NOT allowed class
--FILE--
<?php

use PSX\Sandbox\Tests\FooService;

new FooService();

return 1;

--EXPECT--
1
