<?php

declare(strict_types=1);

namespace SamihSoylu\CipherSuite\PasswordKeyManager;

use Defuse\Crypto\Key;

interface PasswordKeyManagerInterface
{
    public function createProtectedKeyForDb(string $password): string;

    public function encodeKeyForSession(string $protectedKey, string $password): string;

    public function unlockProtectedKey(string $protectedKey, string $password): Key;

    public function decodeSessionKey(string $encodedKey): Key;

    public function encryptData(string $plainText, Key $key): string;

    public function decryptData(string $cipherText, Key $key): string;

    public function updateProtectedKeyPassword(string $protectedKey, $oldPassword, $newPassword): string;
}
