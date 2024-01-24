<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/***
 * @property int $id
 * @property int $product_id
 * @property int $buyer_id
 * @property Product $product
 * @property User $buyer
 * @property ProductShipmentStatus[] $shipmentStatuses
 * @property int $quantity
 * @property int $created_at
 */
class ProductPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function shipmentStatuses(): HasMany
    {
        return $this->hasMany(ProductShipmentStatus::class, 'purchase_id');
    }
}
