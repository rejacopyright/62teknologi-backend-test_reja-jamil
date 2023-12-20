<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class categories extends Model
{
    use HasFactory, Uuids;
    protected $table = 'categories';
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
