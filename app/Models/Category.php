<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/***
 * @property int $id
 * @property string $name
 */
class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        self::NAME,
        self::PARENT_ID,
    ];

    public const string ID = 'id';
    public const string NAME = 'name';
    public const string PARENT_ID = 'parent_id';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }
}
