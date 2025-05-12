<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'field_id', 'rate', 'comment'];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public static function getAllReview()
    {
        return Review::with('customer')->paginate(100);
    }

    public static function getAllCustomerReview()
    {
        return Review::where('customer_id', auth()->guard('customer')->user()->id)->with('customer')->paginate(100);
    }
}
