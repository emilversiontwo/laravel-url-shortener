<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $short_url_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $referer
 * @property string $country
 * @property Carbon $clicked_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ShortUrlClick extends Model
{
    protected $fillable = [
        'short_url_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'clicked_at',
    ];

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
