--TEST--
Creating constants in global namespace with define
--FILE--
<?php

namespace Foo\const {
    define( 'FOO1', 'BAR' );

    return 1;
}

--OPTIONS--
{
    "SecurityManager": {
        "preventGlobalNameSpacePollution": true
    }
}
