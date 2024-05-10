--TEST--
Check static method calls
--FILE--
<?php
return DateTime::createFromFormat( 'Y-m-d', '2024-03-21' )->format('Y-m-d');
--EXPECT--
"2024-03-21"
