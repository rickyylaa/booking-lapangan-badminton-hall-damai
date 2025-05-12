<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCancel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function detail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
