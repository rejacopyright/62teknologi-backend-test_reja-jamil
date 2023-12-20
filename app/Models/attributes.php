<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class attributes extends Model
{
    use HasFactory, Uuids;
    protected $table = 'attributes';
    protected $guarded = [];

    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent', 'scope');
    // }

    public static function boot()
    {
        parent::boot();
        static::generateId();
    }
}
