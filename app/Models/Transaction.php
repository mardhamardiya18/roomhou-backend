<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
        'fee',
        'total_days',
        'price_per_day',

    ];

    public function setListingIdAttribute($value)
    {

        $listing = Listing::find($value);

        $totalDays = Carbon::createFromDate($this->attributes['check_in'])->diffInDays($this->attributes['check_out']) + 1;
        $totalPrice = $totalDays * $listing->price_per_day;
        $fee = $totalPrice * 0.1;

        $this->attributes['total_days'] = $totalDays;
        $this->attributes['price_per_day'] = $listing->price_per_day;
        $this->attributes['total_price'] = $totalPrice + $fee;
        $this->attributes['listing_id'] = $value;
        $this->attributes['fee'] = $fee;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
