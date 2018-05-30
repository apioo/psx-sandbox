--TEST--
Execute dynamic function through array_map
--FILE--
<?php 

$func = 'shell_exec';
array_map($func, ['ls -l']);
