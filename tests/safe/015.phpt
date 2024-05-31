--TEST--
Test use allowed class
--FILE--
<?php

use PSX\Sandbox\Tests\FooService;

new FooService();

return 1;

--EXPECT--
1
--OPTIONS--
{"allowedClasses": ["PSX\\Sandbox\\Tests\\FooService"]}
