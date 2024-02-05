--TEST--
Creating constants in global namespace with define
--FILE--
<?php

define( 'FOO1', 'BAR' );

return 1;

--EXPECT--
1
