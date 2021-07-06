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

namespace PSX\Sandbox\Tests;

use PHPUnit\Framework\TestCase;
use PSX\Sandbox\Runtime;

/**
 * PHPTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
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
                ];
            }
        }

        return $data;
    }

    /**
     * @param string $token
     * @return \PSX\Sandbox\Runtime
     */
    protected function newRuntime($token)
    {
        $runtime = new Runtime($token, null, __DIR__ . '/cache');
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
