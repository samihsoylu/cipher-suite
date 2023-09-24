<?php

declare(strict_types=1);

namespace SamihSoylu\CipherSuite\TransientAesEncryptor;

interface TransientAesEncryptorInterface
{
    public function encrypt(string $plaintext): string;
    public function decrypt(string $encryptedString): string;
}
