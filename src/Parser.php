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
    private ?int $parserType;
    private ParserFactory $parserFactory;

    public function __construct(SecurityManager $securityManager = null, ?int $parserType = null)
    {
        $this->securityManager = $securityManager ?? new SecurityManager();
        $this->parserType      = $parserType;
        $this->parserFactory   = new ParserFactory();
    }

    /**
     * Parses untrusted PHP code and returns a secure version which only 
     * contains safe calls. Throws an exception in case the code contains 
     * untrusted calls
     * 
     * @throws SecurityException
     * @throws ParseException
     * @psalm-suppress RedundantCondition
     */
    public function parse(string $code): string
    {
        if (method_exists($this->parserFactory, 'createForNewestSupportedVersion')) {
            $parser = $this->parserFactory->createForNewestSupportedVersion();
            $printer = new Printer5($this->securityManager);
        } else {
            $parser = $this->parserFactory->create($this->parserType ?? ParserFactory::PREFER_PHP7);
            $printer = new Printer($this->securityManager);
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
