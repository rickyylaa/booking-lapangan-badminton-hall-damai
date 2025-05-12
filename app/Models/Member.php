<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Playing</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Complete</span>';
    }

    public function getStatusNonLabelAttribute()
    {
        if ($this->status == 0) {
            return 'Playing';
        }
        return 'Complete';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function detail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }
}
