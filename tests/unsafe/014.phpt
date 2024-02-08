--TEST--
Bypass function restrictions by declaring namespaced function
--FILE--
<?php

namespace foobar;

function exec() {
    return 1;
}

\exec( 'ls -l' );
