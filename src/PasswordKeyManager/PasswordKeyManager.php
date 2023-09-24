<?php

declare(strict_types=1);

namespace SamihSoylu\CipherSuite\PasswordKeyManager;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\KeyProtectedByPassword;

final class PasswordKeyManager implements PasswordKeyManagerInterface
{
    public function createProtectedKeyForDb(string $password): string
    {
        $protectedKey = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);

        return $protectedKey->saveToAsciiSafeString();
    }

    public function encodeKeyForSession(string $protectedKey, string $password): string
    {
        $protectedKeyObject = KeyProtectedByPassword::loadFromAsciiSafeString($protectedKey);
        $key = $protectedKeyObject->unlockKey($password);

        return $key->saveToAsciiSafeString();
    }

    public function unlockProtectedKey(string $protectedKey, string $password): Key
    {
        $protectedKeyObject = KeyProtectedByPassword::loadFromAsciiSafeString($protectedKey);

        return $protectedKeyObject->unlockKey($password);
    }

    public function decodeSessionKey(string $encodedKey): Key
    {
        return Key::loadFromAsciiSafeString($encodedKey);
    }

    public function encryptData(string $plainText, Key $key): string
    {
        return Crypto::encrypt($plainText, $key);
    }

    public function decryptData(string $cipherText, Key $key): string
    {
        return Crypto::decrypt($cipherText, $key);
    }

    public function updateProtectedKeyPassword(string $protectedKey, $oldPassword, $newPassword): string
    {
        $protectedKeyObject = KeyProtectedByPassword::loadFromAsciiSafeString($protectedKey);
        $protectedKeyObject->changePassword($oldPassword, $newPassword);

        return $protectedKeyObject->saveToAsciiSafeString();
    }
}
