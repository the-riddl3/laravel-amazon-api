<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/***
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property User $seller
 * @property Category $category,
 * @property int $user_id
 * @property int $category_id
*/

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'seller' => new UserResource($this->seller),
            'category' => new CategoryResource($this->category),
        ];
    }
}
