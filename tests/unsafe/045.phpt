--TEST--
Test use with NOT allowed class from global namespace
--FILE--
<?php

use \Reflection;

new Reflection();

return 1;

--EXPECT--
1
