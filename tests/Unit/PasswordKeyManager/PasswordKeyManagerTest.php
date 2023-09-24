<?php

declare(strict_types=1);

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use SamihSoylu\CipherSuite\PasswordKeyManager\PasswordKeyManager;

it('can generate, encode, and decode a protected key', function () {
    $password = 'securePassword';
    $passwordKeyManager = new PasswordKeyManager();

    $protectedKey = $passwordKeyManager->createProtectedKeyForDb($password);
    expect($protectedKey)->toBeString();

    $encodedKey = $passwordKeyManager->encodeKeyForSession($protectedKey, $password);
    expect($encodedKey)->toBeString();

    $decodedKey = $passwordKeyManager->decodeSessionKey($encodedKey);
    $key = $passwordKeyManager->unlockProtectedKey($protectedKey, $password);

    expect($decodedKey)->toEqual($key);
});

it('can encrypt and decrypt data', function () {
    $password = 'securePassword';
    $passwordKeyManager = new PasswordKeyManager();

    $protectedKey = $passwordKeyManager->createProtectedKeyForDb($password);
    $key = $passwordKeyManager->unlockProtectedKey($protectedKey, $password);

    $plainText = 'Sensitive Information';
    $encryptedData = $passwordKeyManager->encryptData($plainText, $key);

    expect($encryptedData)->not()->toEqual($plainText);

    $decryptedData = $passwordKeyManager->decryptData($encryptedData, $key);
    expect($decryptedData)->toEqual($plainText);
});

it('throws an exception if decryption fails', function () {
    $password = 'securePassword';
    $wrongPassword = 'wrongPassword';
    $passwordKeyManager = new PasswordKeyManager();

    $protectedKey = $passwordKeyManager->createProtectedKeyForDb($password);
    $key = $passwordKeyManager->unlockProtectedKey($protectedKey, $password);

    $plainText = 'Sensitive Information';
    $encryptedData = $passwordKeyManager->encryptData($plainText, $key);

    $wrongKey = $passwordKeyManager->unlockProtectedKey($protectedKey, $wrongPassword);
    $passwordKeyManager->decryptData($encryptedData, $wrongKey);  // Should throw an exception
})->throws(WrongKeyOrModifiedCiphertextException::class);

it('can update a protected key password', function () {
    $password = 'securePassword';
    $newPassword = 'newSecurePassword';
    $passwordKeyManager = new PasswordKeyManager();

    $protectedKey = $passwordKeyManager->createProtectedKeyForDb($password);
    $newProtectedKey = $passwordKeyManager->updateProtectedKeyPassword($protectedKey, $password, $newPassword);

    expect($newProtectedKey)->not()->toEqual($protectedKey)
        ->and($newProtectedKey)->toBeString();
});
