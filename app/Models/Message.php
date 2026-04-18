<?php

namespace App\Models;

use App\Traits\EncryptsMessages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use EncryptsMessages, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'media_path',
        'media_type',
        'media_url',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Cifra automáticamente al guardar (solo si hay contenido de texto)
    public function setContentAttribute(?string $value): void
    {
        if ($value === null) {
            $this->attributes['content'] = null;
            return;
        }
        $this->attributes['content'] = $this->encryptMessage($value);
    }

    // Descifra automáticamente al leer (solo si hay contenido de texto)
    public function getContentAttribute(?string $value): ?string
    {
        if ($value === null) return null;
        return $this->decryptMessage($value);
    }

    public function scopeBetweenUsers($query, int $user1, int $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user1)->where('receiver_id', $user2);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user2)->where('receiver_id', $user1);
        });
    }
}