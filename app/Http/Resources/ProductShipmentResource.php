<?php

namespace App\Http\Resources;

use App\Enums\ShipmentState;
use App\Models\ProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/***
 * @property int $id
 * @property int $purchase_id
 * @property ProductPurchase $purchase
 * @property ShipmentState $current_state
 * @property string $current_message
 */
class ProductShipmentResource extends JsonResource
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
            'purchase' => new ProductPurchaseResource($this->purchase),
            'current_state' => $this->current_state->value,
            'current_message' => $this->current_message,
        ];
    }
}
