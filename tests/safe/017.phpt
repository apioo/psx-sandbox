--TEST--
Test use allowed class from global namespace
--FILE--
<?php

namespace foo;

use \DateTimeImmutable;

new DateTimeImmutable( 'now' );

return 1;

--EXPECT--
1
