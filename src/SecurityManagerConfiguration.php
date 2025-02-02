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

/**
 * SecurityManagerConfiguration
 *
 * @author  Antonio Norman
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class SecurityManagerConfiguration
{
    /**
     * @param bool $preventGlobalNameSpacePollution This will prevent creating functions and constanta in the global name space.
     * @param string|null $allowedNamespace Restricts any namespaced code to be the same or a sub-namespace of the value.
     */
    public function __construct(
        private bool $preventGlobalNameSpacePollution = false,
        private ?string $allowedNamespace = null,
    ) {
        $this->setAllowedNamespace($allowedNamespace);
        $this->setPreventGlobalNameSpacePollution($preventGlobalNameSpacePollution);
    }

    public static function fromArray(array $options): self
    {
        $self = new self();

        if (\array_key_exists('allowedNamespace', $options)) {
            $self->setAllowedNamespace($options['allowedNamespace']);
        }

        if (\array_key_exists('preventGlobalNameSpacePollution', $options)) {
            $self->setPreventGlobalNameSpacePollution($options['preventGlobalNameSpacePollution']);
        }

        return $self;
    }

    /**
     * @psalm-mutation-free
     */
    public function preventGlobalNameSpacePollution(): bool
    {
        return $this->preventGlobalNameSpacePollution;
    }

    public function setPreventGlobalNameSpacePollution(bool $preventGlobalNameSpacePollution): void
    {
        $this->preventGlobalNameSpacePollution = $preventGlobalNameSpacePollution;
    }

    /**
     * @psalm-mutation-free
     */
    public function allowedNamespace(): ?string
    {
        return $this->allowedNamespace;
    }

    public function setAllowedNamespace(?string $allowedNamespace): void
    {
        $this->allowedNamespace = $allowedNamespace;
    }
}