<?php

declare(strict_types=1);

use SamihSoylu\CipherSuite\TransientAesEncryptor\EncryptionArtifact;

it('creates an encryption artifact object', function () {
    $ciphertext = 'sampleCiphertext';
    $iv = 'sampleIv';
    $key = 'sampleKey';

    $artifact = EncryptionArtifact::create($ciphertext, $iv, $key);

    expect($artifact->getCiphertext())->toBe($ciphertext)
        ->and($artifact->getIv())->toBe($iv)
        ->and($artifact->getKey())->toBe($key);
});

it('converts the encryption artifact object to a string', function () {
    $ciphertext = 'sampleCiphertext';
    $iv = 'sampleIv';
    $key = 'sampleKey';

    $artifact = EncryptionArtifact::create($ciphertext, $iv, $key);
    $expectedString = $ciphertext . '::' . $iv . '::' . $key;

    expect((string)$artifact)->toBe($expectedString);
});

it('creates an encryption artifact object from a string', function () {
    $ciphertext = 'sampleCiphertext';
    $iv = 'sampleIv';
    $key = 'sampleKey';

    $artifactString = $ciphertext . '::' . $iv . '::' . $key;
    $artifact = EncryptionArtifact::fromString($artifactString);

    expect($artifact->getCiphertext())->toBe($ciphertext)
        ->and($artifact->getIv())->toBe($iv)
        ->and($artifact->getKey())->toBe($key);
});

it('throws an exception for an invalid encryption artifact string', function () {
    $invalidString = 'onlyOneComponent';

    $this->expectException(\UnexpectedValueException::class);
    $this->expectExceptionMessage("Could reconstruct cipher because it is corrupt");

    EncryptionArtifact::fromString($invalidString);
});
