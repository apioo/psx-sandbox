--TEST--
iterator_apply closure callback
--FILE--
<?php

$it = new ArrayIterator(['foo', 'bar']);

iterator_apply($it, function(){
    return true;
}, ['baz']);

return iterator_to_array($it);

--EXPECT--
["foo", "bar"]
