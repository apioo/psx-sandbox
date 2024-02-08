--TEST--
Creating constants in global namespace with CONST
--FILE--
<?php

CONST FOO = 'BAR';

return 1;

--EXPECT--
1
