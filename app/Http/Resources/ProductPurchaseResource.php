<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/***
 * @property int $id
 * @property int $product_id
 * @property int $buyer_id
 * @property Product $product
 * @property User $buyer
 * @property int $quantity
 * @property int $created_at
*/
class ProductPurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => new ProductResource($this->product),
            'buyer' => new UserResource($this->buyer),
            'quantity' => $this->quantity,
            'purchase_date' => $this->created_at,
        ];
    }
}
