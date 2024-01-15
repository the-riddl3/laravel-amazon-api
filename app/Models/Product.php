<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/***
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property User $seller
 * @property int $user_id
 */
class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const PRICE = 'price';

    public const USER_ID = 'user_id';

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::PRICE,
        self::USER_ID,
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
