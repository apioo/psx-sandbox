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

namespace PSX\Sandbox\Tests;

use PHPUnit\Framework\TestCase;
use PSX\Sandbox\Parser;
use PSX\Sandbox\Runtime;
use PSX\Sandbox\SecurityManager;

/**
 * PHPTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
abstract class PHPTestCase extends TestCase
{
    public function caseProvider()
    {
        $path  = $this->getDir();
        $files = scandir($path);
        $data  = [];

        foreach ($files as $file) {
            if (substr($file, -5) == '.phpt' && is_file($path . '/' . $file)) {
                $sections = $this->parseSections($path . '/' . $file);
                $data[] = [
                    $sections['TEST'] ?? null, 
                    $sections['FILE'] ?? null, 
                    isset($sections['EXPECT']) ? json_decode($sections['EXPECT'], true) : null,
                    isset($sections['OPTIONS']) ? json_decode($sections['OPTIONS'], true) : null,
                ];
            }
        }

        return $data;
    }

    /**
     * @param string $token
     * @return \PSX\Sandbox\Runtime
     */
    protected function newRuntime(string $token, ?array $options) : \PSX\Sandbox\Runtime
    {
        $securityManager = new SecurityManager();
        if (isset($options['SecurityManager'])) {
            foreach ($options['SecurityManager'] as $property => $value) {
                $securityManager->{$property} = $value;
            }
        }

        $parser = new Parser( $securityManager );
        $runtime = new Runtime($token, $parser, __DIR__ . '/cache');

        $runtime->set('foo', 'bar');
        $runtime->set('service', new FooService());

        return $runtime;
    }
    
    /**
     * @return string
     */
    abstract protected function getDir();

    /**
     * Parse phpt files into sections, taken from the official PHP source
     *
     * @see https://github.com/php/php-src/blob/master/run-tests.php
     * @param string $file
     * @return array
     */
    private function parseSections($file)
    {
        $lines = file($file);
        $sections = [];
        $section = null;
        foreach ($lines as $line) {
            // Match the beginning of a section.
            if (preg_match('/^--([_A-Z]+)--/', $line, $r)) {
                $section = (string) $r[1];

                if (isset($sections[$section]) && $sections[$section]) {
                    throw new \RuntimeException("duplicated $section section");
                }

                $sections[$section] = '';
                continue;
            }

            if (isset($sections[$section])) {
                $sections[$section] .= $line;
            }
        }

        return $sections;
    }
}
