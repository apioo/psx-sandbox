--TEST--
Prevent creating constants in global namespace
--FILE--
<?php

define( 'FOO', 'BAR' );
--OPTIONS--
{
    "SecurityManager": {
        "preventGlobalNameSpacePollution": true
    }
}
