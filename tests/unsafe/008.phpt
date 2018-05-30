--TEST--
Execute dynamic function through array_map with comment
--FILE--
<?php 

$func = 'shell_exec';
array_map(/* foo */ $func, ['ls -l']);
