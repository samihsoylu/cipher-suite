<?php

declare(strict_types=1);

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use SamihSoylu\CipherSuite\CipherSuite;

it('can generate, encode, and decode a protected key', function () {
    $password = 'securePassword';
    $cipherSuite = new CipherSuite();

    $protectedKey = $cipherSuite->createProtectedKeyForDb($password);
    expect($protectedKey)->toBeString();

    $encodedKey = $cipherSuite->encodeKeyForSession($protectedKey, $password);
    expect($encodedKey)->toBeString();

    $decodedKey = $cipherSuite->decodeSessionKey($encodedKey);
    $key = $cipherSuite->unlockProtectedKey($protectedKey, $password);

    expect($decodedKey)->toEqual($key);
});

it('can encrypt and decrypt data', function () {
    $password = 'securePassword';
    $cipherSuite = new CipherSuite();

    $protectedKey = $cipherSuite->createProtectedKeyForDb($password);
    $key = $cipherSuite->unlockProtectedKey($protectedKey, $password);

    $plainText = 'Sensitive Information';
    $encryptedData = $cipherSuite->encryptData($plainText, $key);

    expect($encryptedData)->not()->toEqual($plainText);

    $decryptedData = $cipherSuite->decryptData($encryptedData, $key);
    expect($decryptedData)->toEqual($plainText);
});

it('throws an exception if decryption fails', function () {
    $password = 'securePassword';
    $wrongPassword = 'wrongPassword';
    $cipherSuite = new CipherSuite();

    $protectedKey = $cipherSuite->createProtectedKeyForDb($password);
    $key = $cipherSuite->unlockProtectedKey($protectedKey, $password);

    $plainText = 'Sensitive Information';
    $encryptedData = $cipherSuite->encryptData($plainText, $key);

    $wrongKey = $cipherSuite->unlockProtectedKey($protectedKey, $wrongPassword);
    $cipherSuite->decryptData($encryptedData, $wrongKey);  // Should throw an exception
})->throws(WrongKeyOrModifiedCiphertextException::class);

it('can update a protected key password', function () {
    $password = 'securePassword';
    $newPassword = 'newSecurePassword';
    $cipherSuite = new CipherSuite();

    $protectedKey = $cipherSuite->createProtectedKeyForDb($password);
    $newProtectedKey = $cipherSuite->updateProtectedKeyPassword($protectedKey, $password, $newPassword);

    expect($newProtectedKey)->not()->toEqual($protectedKey)
        ->and($newProtectedKey)->toBeString();
});
