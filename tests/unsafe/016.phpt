--TEST--
Bypass class restrictions by aliasing class
--FILE--
<?php

use \PSX\Sandbox\Tests\FooService as DateTime;

$a = new DateTime();
