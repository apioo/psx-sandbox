--TEST--
namespaced function
--FILE--
<?php
namespace baz;

function bazFunc( int $i ) : int
{
    return $i;
}

namespace foo\bar;

use function \foo\bar\myFunc as aliasedFunc;
use function \foo\bar\{ myFunc as aliasedFunc2, myFunc as aliasedFunc3 };
use function \baz\bazFunc;
// use \foo; // This doesn't work yet...


function myFunc( int $i ) : int
{
    return $i;
}

return [
    myFunc( 1 ),
    \foo\bar\myFunc( 2 ),
    namespace\myFunc( 3 ),
    aliasedFunc( 4 ),
    aliasedFunc2( 5 ),
    aliasedFunc3( 6 ),
    \baz\bazFunc( 7 ),
//    bazFunc( 8 ), // This doesn't work yet...
//    bar\myFunc( 7 ), // This doesn't work yet...
];

--EXPECT--
[ 1, 2, 3, 4, 5, 6, 7 ]
