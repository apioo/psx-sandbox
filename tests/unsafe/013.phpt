--TEST--
Execute function through array_filter
--FILE--
<?php 

array_filter(['ls -l'], 'shell_exec');
