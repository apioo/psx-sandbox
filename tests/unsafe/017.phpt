--TEST--
Prevent creating constants in global namespace
--FILE--
<?php

CONST FOO = 'BAR';
--OPTIONS--
{
    "SecurityManager": {
        "preventGlobalNameSpacePollution": true
    }
}
