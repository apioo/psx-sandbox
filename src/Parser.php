<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright (c) Christoph Kappestein <christoph.kappestein@gmail.com>
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

use PhpParser\Error;
use PhpParser\ParserFactory;

/**
 * Parser
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Parser
{
    private SecurityManager $securityManager;
    private ParserFactory $parserFactory;

    public function __construct(?SecurityManager $securityManager = null)
    {
        $this->securityManager = $securityManager ?? new SecurityManager();
        $this->parserFactory   = new ParserFactory();
    }

    /**
     * Parses untrusted PHP code and returns a secure version which only 
     * contains safe calls. Throws an exception in case the code contains 
     * untrusted calls
     *
     * @throws ParseException
     * @psalm-suppress RedundantCondition
     */
    public function parse(string $code): string
    {
        $parser = $this->parserFactory->createForNewestSupportedVersion();

        if (defined('PhpParser\ParserFactory::PREFER_PHP7')) {
            $printer = new Printer($this->securityManager);
        } else {
            $printer = new Printer5($this->securityManager);
        }

        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            throw new ParseException($error->getMessage(), 0, $error);
        }

        if ($ast === null) {
            throw new ParseException('Found no tokens');
        }

        return $printer->prettyPrintFile($ast);
    }
}
