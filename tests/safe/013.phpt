--TEST--
Namespace restriction
--FILE--
<?php

namespace foo\baz;

return 1;

--EXPECT--
1
--OPTIONS--
{
    "SecurityManager": {
        "allowedNamespace": "foo"
    }
}
