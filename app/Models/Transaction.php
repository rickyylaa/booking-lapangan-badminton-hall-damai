<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">New</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Pending</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Playing</span>';
        } elseif ($this->status == 3) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Complete</span>';
        } elseif ($this->status == 4) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Waiting</span>';
        } elseif ($this->status == 5) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Cancel</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Playing</span>';
    }

    public function getStatusNonLabelAttribute()
    {
        if ($this->status == 0) {
            return 'New';
        } elseif ($this->status == 1) {
            return 'Pending';
        } elseif ($this->status == 2) {
            return 'Playing';
        } elseif ($this->status == 3) {
            return 'Complete';
        } elseif ($this->status == 4) {
            return 'Waiting';
        } elseif ($this->status == 4) {
            return 'Cancel';
        }
        return 'Playing';
    }

    public function getMemberLabelAttribute()
    {
        if ($this->member_id == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Standard</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Member</span>';
    }

    public function getMemberNonLabelAttribute()
    {
        if ($this->member_id == 0) {
            return 'Standard';
        }
        return 'Member';
    }

    public function getTotalAttribute()
    {
        return $this->amount;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function detail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }

    public function cancel()
    {
        return $this->hasOne(TransactionCancel::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
