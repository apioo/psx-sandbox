--TEST--
Creating constants in namespace with CONST
--FILE--
<?php

namespace Foo\const;

CONST FOO = 'BAR';

return 1;

--EXPECT--
1
--OPTIONS--
{
    "SecurityManager": {
        "preventGlobalNameSpacePollution": true
    }
}
