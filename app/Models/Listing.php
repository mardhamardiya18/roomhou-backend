<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Listing extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'address',
        'sqft',
        'wifi_speed',
        'max_person',
        'price_per_day',
        'full_support_available',
        'gym_area_available',
        'mini_cafe_available',
        'cinema_available',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
