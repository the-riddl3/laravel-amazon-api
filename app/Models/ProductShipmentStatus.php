<?php

namespace App\Models;

use App\Enums\ShipmentState;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/***
 * @property int $id
 * @property int $shipment_id
 * @property ProductShipment $shipment
 * @property ShipmentState $state
 * @property string $message
 * @property Carbon $time
 */
class ProductShipmentStatus extends Model
{
    use HasFactory;

    protected $casts = [
        'state' => ShipmentState::class,
        'time' => 'date',
    ];

    public $timestamps = false;
    protected $guarded = [];

    public const string SHIPMENT_ID = 'shipment_id';
    public const string STATE = 'state';
    public const string MESSAGE = 'message';
    public const string TIME = 'time';


    public function shipment(): BelongsTo
    {
        return $this->belongsTo(ProductShipment::class, self::SHIPMENT_ID);
    }
}
