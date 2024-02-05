<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Sandbox;

use PhpParser\Node;

/**
 * SecurityManager
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class SecurityManager
{
    public bool $preventGlobalNameSpacePollution = false;
    public ?string $allowedNamespace = null;

    private array $allowedFunctions = [
        'func_num_args',
        'func_get_arg',
        'func_get_args',
        'strlen',
        'strcmp',
        'strncmp',
        'strcasecmp',
        'strncasecmp',
        'each',
        'define',
        'defined',
        'get_class',
        'get_called_class',
        'get_parent_class',
        'method_exists',
        'property_exists',
        'class_exists',
        'interface_exists',
        'trait_exists',
        'function_exists',
        'class_alias',
        'is_subclass_of',
        'is_a',
        'get_class_vars',
        'get_object_vars',
        'get_class_methods',
        'bcadd',
        'bcsub',
        'bcmul',
        'bcdiv',
        'bcmod',
        'bcpow',
        'bcsqrt',
        'bcscale',
        'bccomp',
        'bcpowmod',
        'jdtogregorian',
        'gregoriantojd',
        'jdtojulian',
        'juliantojd',
        'jdtojewish',
        'jewishtojd',
        'jdtofrench',
        'frenchtojd',
        'jddayofweek',
        'jdmonthname',
        'easter_date',
        'easter_days',
        'unixtojd',
        'jdtounix',
        'cal_to_jd',
        'cal_from_jd',
        'cal_days_in_month',
        'cal_info',
        'ctype_alnum',
        'ctype_alpha',
        'ctype_cntrl',
        'ctype_digit',
        'ctype_lower',
        'ctype_graph',
        'ctype_print',
        'ctype_punct',
        'ctype_space',
        'ctype_upper',
        'ctype_xdigit',
        'strtotime',
        'date',
        'idate',
        'gmdate',
        'mktime',
        'gmmktime',
        'checkdate',
        'strftime',
        'gmstrftime',
        'time',
        'localtime',
        'getdate',
        'date_create',
        'date_create_immutable',
        'date_create_from_format',
        'date_create_immutable_from_format',
        'date_parse',
        'date_parse_from_format',
        'date_get_last_errors',
        'date_format',
        'date_modify',
        'date_add',
        'date_sub',
        'date_timezone_get',
        'date_timezone_set',
        'date_offset_get',
        'date_diff',
        'date_time_set',
        'date_date_set',
        'date_isodate_set',
        'date_timestamp_set',
        'date_timestamp_get',
        'timezone_open',
        'timezone_name_get',
        'timezone_name_from_abbr',
        'timezone_offset_get',
        'timezone_transitions_get',
        'timezone_location_get',
        'timezone_identifiers_list',
        'timezone_abbreviations_list',
        'timezone_version_get',
        'date_interval_create_from_date_string',
        'date_interval_format',
        'date_default_timezone_set',
        'date_default_timezone_get',
        'date_sunrise',
        'date_sunset',
        'date_sun_info',
        'filter_input',
        'filter_var',
        'filter_input_array',
        'filter_var_array',
        'filter_list',
        'filter_has_var',
        'filter_id',
        'iconv',
        'iconv_get_encoding',
        'iconv_set_encoding',
        'iconv_strlen',
        'iconv_substr',
        'iconv_strpos',
        'iconv_strrpos',
        'iconv_mime_encode',
        'iconv_mime_decode',
        'iconv_mime_decode_headers',
        'json_encode',
        'json_decode',
        'json_last_error',
        'json_last_error_msg',
        'preg_match',
        'preg_match_all',
        'preg_replace',
        'preg_filter',
        'preg_split',
        'preg_quote',
        'preg_grep',
        'preg_last_error',
        'class_parents',
        'class_implements',
        'class_uses',
        'spl_object_hash',
        'iterator_to_array',
        'iterator_count',
        'iterator_apply',
        'constant',
        'bin2hex',
        'hex2bin',
        'wordwrap',
        'htmlspecialchars',
        'htmlentities',
        'html_entity_decode',
        'htmlspecialchars_decode',
        'sha1',
        'md5',
        'crc32',
        'strnatcmp',
        'strnatcasecmp',
        'substr_count',
        'strspn',
        'strcspn',
        'strtok',
        'strtoupper',
        'strtolower',
        'strpos',
        'stripos',
        'strrpos',
        'strripos',
        'strrev',
        'hebrev',
        'hebrevc',
        'nl2br',
        'pathinfo',
        'stripslashes',
        'stripcslashes',
        'strstr',
        'stristr',
        'strrchr',
        'str_shuffle',
        'str_word_count',
        'str_split',
        'strpbrk',
        'substr_compare',
        'strcoll',
        'substr',
        'substr_replace',
        'quotemeta',
        'ucfirst',
        'lcfirst',
        'ucwords',
        'strtr',
        'addslashes',
        'addcslashes',
        'rtrim',
        'str_contains',
        'str_starts_with',
        'str_ends_with',
        'str_decrement',
        'str_increment',
        'str_replace',
        'str_ireplace',
        'str_repeat',
        'count_chars',
        'chunk_split',
        'trim',
        'ltrim',
        'strip_tags',
        'similar_text',
        'explode',
        'implode',
        'join',
        'soundex',
        'levenshtein',
        'chr',
        'ord',
        'parse_str',
        'str_getcsv',
        'str_pad',
        'chop',
        'strchr',
        'sprintf',
        'vsprintf',
        'sscanf',
        'parse_url',
        'urlencode',
        'urldecode',
        'rawurlencode',
        'rawurldecode',
        'http_build_query',
        'rand',
        'srand',
        'getrandmax',
        'mt_rand',
        'mt_srand',
        'mt_getrandmax',
        'random_bytes',
        'random_int',
        'base64_decode',
        'base64_encode',
        'password_hash',
        'password_get_info',
        'password_needs_rehash',
        'password_verify',
        'abs',
        'ceil',
        'floor',
        'round',
        'sin',
        'cos',
        'tan',
        'asin',
        'acos',
        'atan',
        'atanh',
        'atan2',
        'sinh',
        'cosh',
        'tanh',
        'asinh',
        'acosh',
        'expm1',
        'log1p',
        'pi',
        'is_finite',
        'is_nan',
        'is_infinite',
        'pow',
        'exp',
        'log',
        'log10',
        'sqrt',
        'hypot',
        'deg2rad',
        'rad2deg',
        'bindec',
        'hexdec',
        'octdec',
        'decbin',
        'decoct',
        'dechex',
        'base_convert',
        'number_format',
        'fmod',
        'intdiv',
        'inet_ntop',
        'inet_pton',
        'ip2long',
        'long2ip',
        'microtime',
        'gettimeofday',
        'uniqid',
        'quoted_printable_decode',
        'quoted_printable_encode',
        'intval',
        'floatval',
        'doubleval',
        'strval',
        'boolval',
        'gettype',
        'settype',
        'is_null',
        'is_resource',
        'is_bool',
        'is_int',
        'is_float',
        'is_integer',
        'is_long',
        'is_double',
        'is_real',
        'is_numeric',
        'is_string',
        'is_array',
        'is_object',
        'is_scalar',
        'is_callable',
        'is_iterable',
        'pack',
        'unpack',
        'ksort',
        'krsort',
        'natsort',
        'natcasesort',
        'asort',
        'arsort',
        'sort',
        'rsort',
        'usort',
        'uasort',
        'uksort',
        'shuffle',
        'array_walk',
        'array_walk_recursive',
        'count',
        'end',
        'prev',
        'next',
        'reset',
        'current',
        'key',
        'min',
        'max',
        'in_array',
        'array_search',
        'extract',
        'compact',
        'array_fill',
        'array_fill_keys',
        'range',
        'array_multisort',
        'array_push',
        'array_pop',
        'array_shift',
        'array_unshift',
        'array_splice',
        'array_slice',
        'array_merge',
        'array_merge_recursive',
        'array_replace',
        'array_replace_recursive',
        'array_keys',
        'array_values',
        'array_count_values',
        'array_column',
        'array_reverse',
        'array_reduce',
        'array_pad',
        'array_flip',
        'array_change_key_case',
        'array_rand',
        'array_unique',
        'array_intersect',
        'array_intersect_key',
        'array_intersect_assoc',
        'array_diff',
        'array_diff_key',
        'array_diff_assoc',
        'array_sum',
        'array_product',
        'array_filter',
        'array_map',
        'array_chunk',
        'array_combine',
        'array_key_exists',
        'pos',
        'sizeof',
        'key_exists',
    ];

    private array $allowedClasses = [
        'stdClass',
        'Exception',
        'ErrorException',
        'Error',
        'ParseError',
        'TypeError',
        'ArgumentCountError',
        'ArithmeticError',
        'DivisionByZeroError',
        'Closure',
        'DateTime',
        'DateTimeImmutable',
        'DateTimeZone',
        'DateInterval',
        'DatePeriod',
        'LogicException',
        'BadFunctionCallException',
        'BadMethodCallException',
        'DomainException',
        'InvalidArgumentException',
        'LengthException',
        'OutOfRangeException',
        'RuntimeException',
        'OutOfBoundsException',
        'OverflowException',
        'RangeException',
        'UnderflowException',
        'UnexpectedValueException',
        'RecursiveIteratorIterator',
        'IteratorIterator',
        'FilterIterator',
        'RecursiveFilterIterator',
        'ParentIterator',
        'LimitIterator',
        'CachingIterator',
        'RecursiveCachingIterator',
        'NoRewindIterator',
        'AppendIterator',
        'InfiniteIterator',
        'RegexIterator',
        'RecursiveRegexIterator',
        'EmptyIterator',
        'RecursiveTreeIterator',
        'ArrayObject',
        'ArrayIterator',
        'RecursiveArrayIterator',
        'SplTempFileObject',
        'SplDoublyLinkedList',
        'SplQueue',
        'SplStack',
        'SplHeap',
        'SplMinHeap',
        'SplMaxHeap',
        'SplPriorityQueue',
        'SplFixedArray',
        'SplObjectStorage',
        'MultipleIterator',
        'SimpleXMLElement',
        'SimpleXMLIterator',
        'NumberFormatter',
        'Swift_Message',
    ];

    private array $functionAliases = [];
    private array $classAliases = [];

    private null|string $currentNamespace = null;


    public function setAllowedFunctions(array $allowedFunctions)
    {
        $this->allowedFunctions = $allowedFunctions;
    }

    public function addAllowedFunction(string $functionName): void
    {
        if ($this->currentNamespace !== null) {
            $functionName = "{$this->currentNamespace}\\{$functionName}";
        }

        if (!\in_array($functionName, $this->allowedFunctions, true)) {
            $this->allowedFunctions[] = $functionName;
        }
    }

    public function setAllowedClasses(array $allowedClasses)
    {
        $this->allowedClasses = $allowedClasses;
    }

    public function addAllowedClass(string $className)
    {
        $this->allowedClasses[] = $className;
    }

    public function setCurrentNamespace(?string $currentNamespace): void
    {
        if ($currentNamespace !== null) {
            $currentNamespace = \ltrim($currentNamespace, '\\');

            if (
                $this->allowedNamespace !== null
                && !str_starts_with($currentNamespace, \ltrim($this->allowedNamespace, '\\'))
            ) {
                throw new SecurityException("Namespace {$currentNamespace} is outside of allowed namespace {$this->allowedNamespace}" );
            }
        }

        $this->currentNamespace = !empty($currentNamespace) ? $currentNamespace : null;
    }

    public function currentNamespace(): null|string
    {
        return $this->currentNamespace;
    }

    public function addFunctionAlias(string $function, string $alias): void
    {
        $this->functionAliases[$alias] = $function;
    }

    public function addClassAlias(string $class, string $alias): void
    {
        $this->classAliases[$alias] = $class;
    }

    /**
     * @throws SecurityException
     */
    public function checkFunctionCall(string $functionName, array $arguments = [])
    {
        if (isset($this->functionAliases[$functionName])) {
            $functionName = $this->functionAliases[$functionName];
        }

        $functionName = $this->fullyQualifyNamespacedFunction($functionName);

        $functionName = ltrim($functionName, '\\');

        if (!in_array($functionName, $this->allowedFunctions)) {
            throw new SecurityException('Call to a not allowed function ' . $functionName);
        }

        if ( $functionName === 'define' )
        {
            $this->checkDefineDefine();
            return;
        }

        // check specific function arguments
        if ($functionName === 'array_map') {
            $callable = $this->getArgumentAt($functionName, $arguments, 0);
            if ($callable instanceof Node\Arg) {
                $this->checkCallable($callable);
            } else {
                throw new SecurityException('array_map missing callable at position 0');
            }
        } elseif ($functionName === 'iterator_apply' || $functionName === 'array_walk' || $functionName === 'array_walk_recursive' || $functionName === 'array_reduce' || $functionName === 'array_filter') {
            $callable = $this->getArgumentAt($functionName, $arguments, 1);
            if ($callable instanceof Node\Arg) {
                $this->checkCallable($callable);
            } else {
                throw new SecurityException($functionName . ' missing callable at position 1');
            }
        } elseif ($functionName === 'usort' || $functionName === 'uasort' || $functionName === 'uksort') {
            $callable = $this->getArgumentAt($functionName, $arguments, 1);
            if ($callable instanceof Node\Arg) {
                $this->checkCallable($callable);
            } else {
                throw new SecurityException($functionName . ' missing callable at position 1');
            }
        }
    }

    private function fullyQualifyNamespacedFunction(string $functionName): string
    {
        // check \<function> <= explicit call to global function
        if (\preg_match('/^\\\\[\w]+$/', $functionName)) {
            return $functionName;
        }

        // check <function>
        if (!\str_contains($functionName, '\\')) {
            if ($this->currentNamespace === null) {
                return $functionName;
            }

            // Check if the function has been created/allowed in the current namespace else use global
            $_functionName = "{$this->currentNamespace()}\\{$functionName}";
            return \in_array($_functionName, $this->allowedFunctions, true) ? $_functionName : $functionName;
        }
        // check namespace\<function>
        elseif ($this->currentNamespace !== null && \str_starts_with($functionName, 'namespace\\')) {
            return "{$this->currentNamespace()}\\" . \substr($functionName, \strlen('namespace\\'));
        }
        // check \foo\<function> or foo\<function>
        elseif (\preg_match_all('/^(\\\\?[\w\\\\]+)\\\\(\w+)$/', $functionName, $matches)) {
            return \ltrim($matches[1][0], '\\') . "\\{$matches[2][0]}";
        }

        return $functionName;
    }

    /**
     * @throws SecurityException
     */
    public function checkNewCall(string $className)
    {
        if (isset($this->classAliases[$className])) {
            $className = $this->classAliases[$className];
        }

        $className = ltrim($className, '\\');

        if (!in_array($className, $this->allowedClasses)) {
            throw new SecurityException('Call to a not allowed class ' . $className);
        }
    }

    /**
     * @throws SecurityException
     */
    private function checkCallable(Node\Arg $callable)
    {
        $value = $callable->value;
        if ($value instanceof Node\Scalar\String_) {
            $this->checkFunctionCall($value->value);
        } elseif ($value instanceof Node\Expr\Closure) {
        } else {
            throw new SecurityException('Usage of an invalid callable type, only string and closure allowed');
        }
    }

    /**
     * @throws SecurityException
     */
    public function defineFunction(string $functionName): void
    {
        $this->checkDefineFunction();

        $this->addAllowedFunction($functionName);
    }

    /**
     * @throws SecurityException
     */
    public function checkDefineFunction(): void
    {
        if ($this->preventGlobalNameSpacePollution && $this->currentNamespace === null) {
            throw new SecurityException('Defining functions in global namespace is not allowed');
        }
    }

    /**
     * @throws SecurityException
     */
    public function checkDefineConstant(): void
    {
        if ($this->preventGlobalNameSpacePollution && $this->currentNamespace === null) {
            throw new SecurityException('Defining constants in global namespace is not allowed');
        }
    }

    public function checkDefineDefine(): void
    {
        if ($this->preventGlobalNameSpacePollution) {
            throw new SecurityException('Defining constants in global namespace is not allowed');
        }
    }

    private function getArgumentAt(string $functionName, array $nodes, int $pos): ?Node\Arg
    {
        $nodes = array_filter($nodes, function ($node) {
            return $node instanceof Node\Arg;
        });

        /** @var callable-string $functionName */
        $reflection = new \ReflectionFunction($functionName);
        $name = $reflection->getParameters()[$pos]->getName();

        /** @var Node\Arg $node */
        foreach ($nodes as $node) {
            if ((string)$node->name === $name) {
                return $node;
            }
        }

        return $nodes[$pos] ?? null;
    }
}
