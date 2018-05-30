--TEST--
Execute dynamic function through iterator_apply
--FILE--
<?php 

$func = 'shell_exec';
iterator_apply(new ArrayIterator(['foo']), $func, ['ls -l']);
