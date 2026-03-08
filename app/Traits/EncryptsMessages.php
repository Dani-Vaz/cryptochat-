<?php

namespace App\Traits;

use App\Services\EncryptionService;

trait EncryptsMessages
{
    protected function encryptionService(): EncryptionService
    {
        return app(EncryptionService::class);
    }

    protected function encryptMessage(string $content): string
    {
        return $this->encryptionService()->encrypt($content);
    }

    protected function decryptMessage(string $encryptedContent): string
    {
        return $this->encryptionService()->decrypt($encryptedContent);
    }
}
