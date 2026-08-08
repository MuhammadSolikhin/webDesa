<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
