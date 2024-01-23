<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/***
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property User $seller
 * @property Category $category
 * @property ProductPurchase[] $purchases
 * @property int $user_id
 * @property int $category_id
 */
class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const string NAME = 'name';
    public const string DESCRIPTION = 'description';
    public const string PRICE = 'price';

    public const string USER_ID = 'user_id';
    public const string CATEGORY_ID = 'category_id';

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::PRICE,
        self::USER_ID,
        self::CATEGORY_ID,
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class);
    }
}
