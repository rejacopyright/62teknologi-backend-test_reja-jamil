<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class business extends Model
{
    use HasFactory, Uuids;
    protected $table = 'business';
    protected $guarded = [];
    protected $casts = [
        'attribute_ids' => 'array',
    ];

    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent', 'scope');
    // }
    public function location()
    {
        return $this->belongsTo(location::class, 'location_id', 'id');
    }
    public function term()
    {
        return $this->belongsTo(term::class, 'term_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id', 'id');
    }
    // public function attribute()
    // {
    //     // return $this->belongsToMany(attributes::class, self::class, 'id', 'attribute_ids');
    //     return $this->belongsTo(attributes::class, 'attribute_ids', 'id');
    // }

    public static function boot()
    {
        parent::boot();
        static::generateId();
    }
}
