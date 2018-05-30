--TEST--
Execute function through array_walk
--FILE--
<?php 

array_walk(['ls -l'], 'shell_exec');
