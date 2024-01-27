<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Http\Resources\UserAddressResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/***
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property UserRole $role
 * @property ProductPurchase[] $purchases
 * @property UserAddress[] $addresses
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public const string ID = 'id';
    public const string NAME = 'name';
    public const string EMAIL = 'email';
    public const string PASSWORD = 'password';
    public const string ROLE = 'role';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class, 'buyer_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
}
