<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
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

/**
 * Runtime
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Runtime
{
    private string $token;
    private Parser $parser;
    private ?string $cachePath;
    private array $context;

    /**
     * Creates a new runtime. Every runtime has a specific security token which 
     * is used to determine the file name where we store the clean code
     */
    public function __construct(string $token, ?Parser $parser = null, ?string $cachePath = null)
    {
        $this->token     = $token;
        $this->parser    = $parser ?? new Parser();
        $this->cachePath = $cachePath === null ? sys_get_temp_dir() : $cachePath;
        $this->context   = [];
    }

    public function set(string $name, mixed $value)
    {
        $this->context[$name] = $value;
    }

    /**
     * @throws ParseException
     * @throws SecurityException
     */
    public function run(string $code): mixed
    {
        $file = $this->cachePath . '/runtime_' . substr(md5($this->token), 0, 8) . '.php';

        // write file if it does not exist or the code has changed
        if (!is_file($file) || md5_file($file) != md5($code)) {
            file_put_contents($file, $this->parser->parse($code));
        }

        return runIsolate($file, $this->context);
    }
}

function runIsolate($file, array $context)
{
    extract($context);
    return include $file;
}
