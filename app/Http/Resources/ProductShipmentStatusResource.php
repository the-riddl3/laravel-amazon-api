<?php

namespace App\Http\Resources;

use App\Enums\ShipmentState;
use App\Models\ProductPurchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/***
 * @property int $id
 * @property int $purchase_id
 * @property ProductPurchase $purchase
 * @property ShipmentState $state
 * @property string $message
 * @property Carbon $time
 */
class ProductShipmentStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'state' => $this->state->value,
            'time' => $this->time->toString(),
            'purchase' => new ProductPurchaseResource($this->purchase),
        ];
    }
}
