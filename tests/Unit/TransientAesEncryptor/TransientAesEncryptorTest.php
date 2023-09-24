<?php

declare(strict_types=1);

use SamihSoylu\CipherSuite\TransientAesEncryptor\TransientAesEncryptor;

it('encrypts and then decrypts a string', function () {
    $encryptor = new TransientAesEncryptor();
    $originalText = 'Hello, world!';

    $encrypted = $encryptor->encrypt($originalText);
    $decrypted = $encryptor->decrypt($encrypted);

    expect($decrypted)->toBe($originalText);
});

it('throws an exception when decrypting an invalid string', function () {
    $encryptor = new TransientAesEncryptor();

    $iv = base64_encode(random_bytes(16));
    $invalidString = "some::{$iv}::string";

    $this->expectException(UnexpectedValueException::class);
    $this->expectExceptionMessage('Could decrypt cipher because it is corrupt');

    $encryptor->decrypt($invalidString);
});

it('ensures encrypted output is different from the input', function () {
    $encryptor = new TransientAesEncryptor();
    $originalText = 'Hello, world!';

    $encrypted = $encryptor->encrypt($originalText);

    expect($encrypted)->not()->toBe($originalText);
});

it('ensures different calls produce different encrypted outputs', function () {
    $encryptor = new TransientAesEncryptor();
    $originalText = 'Hello, world!';

    $encrypted1 = $encryptor->encrypt($originalText);
    $encrypted2 = $encryptor->encrypt($originalText);

    expect($encrypted1)->not()->toBe($encrypted2);
});
