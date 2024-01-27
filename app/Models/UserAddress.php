<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property User $user
 * @property string $address_line
 */
class UserAddress extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public const string USER_ID = 'user_id';
    public const string ADDRESS_LINE = 'address_line';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
