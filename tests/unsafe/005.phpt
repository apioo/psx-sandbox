--TEST--
Execute variable function name
--FILE--
<?php 

$func = 'shell_exec';
$func('ls -l');
