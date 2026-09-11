<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image', 'kml_file'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
