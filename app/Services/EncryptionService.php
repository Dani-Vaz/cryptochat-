<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionService
{
    public function encrypt(string $message): string
    {
        return Crypt::encryptString($message);
    }

    public function decrypt(string $encryptedMessage): string
    {
        try {
            return Crypt::decryptString($encryptedMessage);
        } catch (DecryptException $e) {
            return '[Error: No se pudo descifrar]';
        }
    }
}
