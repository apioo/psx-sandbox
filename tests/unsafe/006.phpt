--TEST--
Execute function through array_map
--FILE--
<?php 

array_map('shell_exec', ['ls -l']);
