--TEST--
Bypass function restrictions by aliasing function
--FILE--
<?php

use function \exec as intval;

intval( 'ls -l' );
