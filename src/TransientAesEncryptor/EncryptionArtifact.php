<?php

declare(strict_types=1);

namespace SamihSoylu\CipherSuite\TransientAesEncryptor;

final class EncryptionArtifact
{
    private string $ciphertext;
    private string $iv;
    private string $key;

    private function __construct()
    {
    }

    public function getCiphertext(): string
    {
        return $this->ciphertext;
    }

    public function getIv(): string
    {
        return $this->iv;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public static function create(string $ciphertext, string $iv, string $key): self
    {
        $self = new self();

        $self->ciphertext = $ciphertext;
        $self->iv = $iv;
        $self->key = $key;

        return $self;
    }

    public function __toString(): string
    {
        return implode('::', [
            $this->ciphertext,
            $this->iv,
            $this->key,
        ]);
    }

    public static function fromString(string $value): self
    {
        $self = new self();

        $properties = explode('::', $value);
        if (count($properties) !== 3) {
            throw new \UnexpectedValueException(
                "Could reconstruct cipher because it is corrupt"
            );
        }

        [$self->ciphertext, $self->iv, $self->key] = $properties;

        return $self;
    }
}
