<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
