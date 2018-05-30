--TEST--
Execute dynamic function through usort
--FILE--
<?php 

$cmds = ['ls -l'];
usort($cmds, 'shell_exec');
