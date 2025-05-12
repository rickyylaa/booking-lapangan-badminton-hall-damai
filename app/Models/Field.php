<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'price', 'image', 'description', 'condition', 'status'];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Inactive</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Active</span>';
    }

    public function getStatusNonLabelAttribute()
    {
        if ($this->status == 0) {
            return 'Inactive';
        }
        return 'Active';
    }

    public function getConditionLabelAttribute()
    {
        if ($this->condition == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Available</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Booked</span>';
    }

    public function getConditionNonLabelAttribute()
    {
        if ($this->condition == 0) {
            return 'Available';
        }
        return 'Booked';
    }

    public function getReview()
    {
        return $this->hasMany('App\Models\Review', 'field_id', 'id')->with('customer')->orderBy('id', 'ASC');
    }

    public static function getFieldBySlug($slug)
    {
        return Field::with(['getReview'])->where('slug', $slug)->first();
    }

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
