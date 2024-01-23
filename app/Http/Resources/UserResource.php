<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/***
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property UserRole $role
*/
class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}
