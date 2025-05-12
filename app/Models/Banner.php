<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image', 'description', 'status'];

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
}
