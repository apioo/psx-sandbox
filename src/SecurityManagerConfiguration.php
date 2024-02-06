<?php


namespace PSX\Sandbox;


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

    public function preventGlobalNameSpacePollution(): bool
    {
        return $this->preventGlobalNameSpacePollution;
    }

    public function setPreventGlobalNameSpacePollution(bool $preventGlobalNameSpacePollution): void
    {
        $this->preventGlobalNameSpacePollution = $preventGlobalNameSpacePollution;
    }

    public function allowedNamespace(): ?string
    {
        return $this->allowedNamespace;
    }

    public function setAllowedNamespace(?string $allowedNamespace): void
    {
        $this->allowedNamespace = $allowedNamespace;
    }
}