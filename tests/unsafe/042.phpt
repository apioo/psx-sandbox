--TEST--
Test wrapping function in closure
--FILE--
<?php
return ( Closure::fromCallable( 'exec' ) )( 'ls' );
?>
