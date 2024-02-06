
# Sandbox

## About

This library helps to execute PHP code which was provided by a user. I.e. if
your app wants to provide a scripting feature where the user can provide custom
PHP code. This library helps to parse this untrusted code and execute it only
if there are safe calls.

Internally the library uses the [PHPParser](https://github.com/nikic/PHP-Parser)
library to parse the code. To make the code safe the sandbox allows only a small 
subset of PHP. It is not possible to define a class, interface or trait. Also 
all functions which produce output i.e. echo, print, etc. are not allowed. The 
only way a user can return a value is by using the `return` statement in the 
script. Every function and new call gets checked by the security manager. The 
security manager contains a whitelist of all allowed functions and classes. 

Its important to note that the security check is _not_ performed on runtime, 
instead we simply prevent to generate PHP code which contains untrusted code.
Because of this features like i.e. dynamic functions names 
`$func = 'foo'; $func();` are also not allowed.

If the code is clean the runtime generates normal PHP code and writes this to a 
file. All subsequent calls simply include and execute this code, thus the 
sandbox has almost no performance loss.

## Security

It is not recommended to run PHP code from anonymous users on the internet. This 
feature is intended to be used i.e. by customers of a SAAS solution which need 
to customize specific parts of the app. If you have found a way to breakout of 
the sandbox please open an issue or if you like you can also create a pull 
request with a fitting test case. Please take a look at the tests folder to see 
already covered cases.

## Usage

```php
<?php

$code = <<<'CODE'
<?php

return [
    'result' => $my_service,
];

CODE;

$runtime = new \PSX\Sandbox\Runtime('my_code');
$runtime->set('my_service', 'foo');
$response = $runtime->run($code);
```

### Advanced configuration
Configurations are set by passing an instance of `\PSX\Sandbox\SecurityManagerConfiguration`
```php
<?php

$config = new \PSX\Sandbox\SecurityManagerConfiguration( 
    preventGlobalNameSpacePollution: true
);
$securityManager = new \PSX\Sandbox\SecurityManager($securityManagerConfig);
$parser = new \PSX\Sandbox\Parser($securityManager);
        
$runtime = new \PSX\Sandbox\Runtime('my_code', $parser);
$runtime->set('my_service', 'foo');
$response = $runtime->run( '<php? return $my_service;' );
```
* preventGlobalNameSpacePollution (bool): This will prevent creating functions and constants in the global name space.
* allowedNamespace (null|string): Restricts any namespaced code to be the same or a sub-namespace of the value.

## Requirements
* PHP 8.0+

## Installation
Install with composer `composer require psx/sandbox`