<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }
}
