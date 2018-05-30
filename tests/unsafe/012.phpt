--TEST--
Execute function through array_reduce
--FILE--
<?php 

array_reduce(['ls -l'], 'shell_exec');
