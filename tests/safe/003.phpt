--TEST--
Method call
--FILE--
<?php
return ['foo' => $service->getTitle()]; ?>
--EXPECT--
{"foo": "foo"}
