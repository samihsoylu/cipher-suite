# CipherSuite
CipherSuite is a lightweight PHP wrapper for the [Defuse PHP-Encryption library](https://github.com/defuse/php-encryption), designed to simplify key management, session key encoding, and data encryption/decryption. It's perfect for developers looking for a straightforward way to integrate cryptographic operations into their applications.

## Features
* Easy-to-use API for generating protected keys for database storage
* Convenient methods for encoding and decoding session keys
* Simplified encryption and decryption methods
* Support for password updates

## Requirements
* PHP 7.4 or newer

## Installation
```sh
composer require samihsoylu/cipher-suite
```

## Usage

### Create a Protected Key for Database Storage
A **protected key** should be generated to be safely stored in a database. This key can later be encoded for temporary storage in a user's session, or can be directly unlocked to use for cryptographic operations.
```php
$cipherSuite = new CipherSuite();
$protectedKey = $cipherSuite->createProtectedKeyForDb('password');
```

### Encode a Key for Session Storage
After generating a protected key, the `encodeKeyForSession` method allows you to transform this key into an encoded form suitable for storage in a user's session. This encoded key is pivotal for performing encryption and decryption activities during a user's session.
```php
$encodedKey = $cipherSuite->encodeKeyForSession($protectedKey, 'password');
```

### Decode a Session Key
If you have previously stored an encoded key in the user's session and wish to perform encryption or decryption operations, the `decodeSessionKey` method converts the encoded key back into a usable `Key` object. You can encrypt/decrypt using this Key object
```php
$key = $cipherSuite->decodeSessionKey($encodedKey);
```

### Unlock a Protected Key
The `unlockProtectedKey` method is designed for scenarios where you retrieve a protected key from persistent storage, such as a database, and need to use it for encryption or decryption. This feature is especially helpful during web application events like account creation, where you might want to securely encrypt default user data using a freshly unlocked key.
```php
$key = $cipherSuite->unlockProtectedKey($protectedKey, 'password');
```

### Encrypt Data
Utilize the `encryptData` method to secure sensitive data using either a freshly unlocked key or a decoded session key. This ensures that the encrypted data remains confidential, accessible only to entities possessing the original key.
```php
$encryptedData = $cipherSuite->encryptData('plainText', $key);
```

### Decrypt Data
The `decryptData` method enables you to retrieve the original content from encrypted data. It's crucial to catch exceptions when using this method, as decryption might fail if the ciphertext has been tampered with or if an incorrect key is used.
```php
$plainText = $cipherSuite->decryptData($encryptedData, $key);
```

### Update Protected Key Password
The `updateProtectedKeyPassword` method allows for secure password updates for protected keys without requiring you to re-encrypt existing encrypted data. This is ideal for situations like user password changes, where the underlying data does not need to be modified, only the key protection mechanism.
```php
$newProtectedKey = $cipherSuite->updateProtectedKeyPassword($protectedKey, 'oldPassword', 'newPassword');
```

## Exception Handling
* **EnvironmentIsBrokenException**: Thrown when cryptographic primitives fail. Make sure your environment meets the library's requirements.
* **BadFormatException**: Thrown when a key has a bad format. Ensure the keys are generated and stored correctly.
* **WrongKeyOrModifiedCiphertextException**: Thrown when the provided key is incorrect or the ciphertext has been modified. Make sure to handle these exceptions gracefully.