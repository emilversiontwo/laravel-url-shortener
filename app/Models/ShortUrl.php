<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $url
 * @property string $alias
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class ShortUrl extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'alias',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shortUrlClicks(): HasMany
    {
        return $this->hasMany(ShortUrlClick::class);
    }
}
