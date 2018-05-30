--TEST--
Array map closure callback
--FILE--
<?php
return array_map(function($val){
    return ucfirst($val);
}, ['foo', 'bar']);

--EXPECT--
["Foo", "Bar"]
