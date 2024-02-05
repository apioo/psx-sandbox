--TEST--
Namespace restriction
--FILE--
<?php

namespace baz;

return 1;
--OPTIONS--
{
    "SecurityManager": {
        "allowedNamespace": "foo"
    }
}
