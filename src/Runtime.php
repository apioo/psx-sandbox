<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \PSX\Sandbox\Parser
     */
    protected $parser;

    /**
     * @var string
     */
    protected $cachePath;

    /**
     * @var array
     */
    protected $context;

    /**
     * Creates a new runtime. Every runtime has a specific security token which 
     * is used to determine the file name where we store the clean code
     * 
     * @param string $token
     * @param \PSX\Sandbox\Parser|null $parser
     * @param string|null $cachePath
     */
    public function __construct($token, Parser $parser = null, $cachePath = null)
    {
        $this->token     = $token;
        $this->parser    = $parser ?? new Parser();
        $this->cachePath = $cachePath === null ? sys_get_temp_dir() : $cachePath;
        $this->context   = [];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->context[$name] = $value;
    }

    /**
     * @param string $code
     * @return mixed
     * @throws \PSX\Sandbox\ParseException
     * @throws \PSX\Sandbox\SecurityException
     */
    public function run($code)
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
