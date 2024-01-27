<?php

namespace App\Models;

use App\Enums\ShipmentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/***
 * @property int $id
 * @property int $purchase_id
 * @property ProductPurchase $purchase
 * @property ShipmentState $current_state
 * @property string $current_message
 * @property ProductShipmentStatus[] $statuses
 * @property int $user_address_id
 * @property UserAddress $userAddress
 */
class ProductShipment extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'current_state' => ShipmentState::class,
    ];

    public const string PURCHASE_ID = 'purchase_id';
    public const string CURRENT_STATE = 'current_state';
    public const string CURRENT_MESSAGE = 'current_message';
    public const string USER_ADDRESS_ID = 'user_address_id';

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(ProductPurchase::class, 'purchase_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(ProductShipmentStatus::class, 'shipment_id');
    }

    public function userAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }
}
