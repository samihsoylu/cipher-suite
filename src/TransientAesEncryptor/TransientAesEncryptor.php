<?php

declare(strict_types=1);

namespace SamihSoylu\CipherSuite\TransientAesEncryptor;

use UnexpectedValueException;

/**
 * A utility class designed for short-term data encryption. Not recommended for persistent or high-security requirements.
 */
final class TransientAesEncryptor implements TransientAesEncryptorInterface
{
    public function encrypt(string $plaintext): string
    {
        $encryptionKey = openssl_random_pseudo_bytes(32);

        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);

        $ciphertext = openssl_encrypt($plaintext, $cipher, $encryptionKey, $options = 0, $iv);

        return (string) EncryptionArtifact::create($ciphertext, base64_encode($iv), base64_encode($encryptionKey));
    }

    public function decrypt(string $encryptedString): string
    {
        $payload = EncryptionArtifact::fromString($encryptedString);

        $plaintext = openssl_decrypt(
            $payload->getCiphertext(),
            "AES-256-CBC",
            base64_decode($payload->getKey(), true),
            $options = 0,
            base64_decode($payload->getIv(), true),
        );

        if ($plaintext === false) {
            throw new UnexpectedValueException(
                'Could decrypt cipher because it is corrupt'
            );
        }

        return $plaintext;
    }
}
