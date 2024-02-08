--TEST--
global varaible test
--FILE--
<?php
global $a;
$a = null;
return $a;
?>
