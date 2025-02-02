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

namespace PSX\Sandbox\Tests;

use PHPUnit\Framework\TestCase;
use PSX\Sandbox\Parser;
use PSX\Sandbox\Runtime;
use PSX\Sandbox\SecurityManager;
use PSX\Sandbox\SecurityManagerConfiguration;

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

                foreach (['EXPECT', 'OPTIONS'] as $key) {
                    try {
                        $decodedValues[$key] = isset($sections[$key]) ? \json_decode(
                            $sections[$key],
                            true,
                            512,
                            \JSON_THROW_ON_ERROR
                        ) : null;
                    } catch (\JsonException $exception) {
                        throw new \Exception("Invalid JSON for {$key} in file {$path}/{$file}\n" . $exception->getMessage(), 0, $exception);
                    }
                }

                $data[] = [
                    $sections['TEST'] ?? null,
                    $sections['FILE'] ?? null,
                    $decodedValues['EXPECT'],
                    $decodedValues['OPTIONS'],
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
        $securityManagerConfig = null;
        if (isset($options['SecurityManager']) && \is_array($options['SecurityManager'])) {
            $securityManagerConfig = SecurityManagerConfiguration::fromArray($options['SecurityManager']);
        }

        $securityManager = new SecurityManager($securityManagerConfig);

        if (isset($options['allowedClasses']) && \is_array($options['allowedClasses'])) {
            foreach ($options['allowedClasses'] as $allowedClass) {
                $securityManager->addAllowedClass($allowedClass);
            }
        }

        $parser = new Parser($securityManager);

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
