--TEST--
Prevent creating functions in global namespace
--FILE--
<?php

function foobar() : void {

}
--OPTIONS--
{
    "SecurityManager": {
        "preventGlobalNameSpacePollution": true
    }
}
