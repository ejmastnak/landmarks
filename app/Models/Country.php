<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    public function landmarks()
    {
        return $this->hasMany(Landmark::class, 'country_id', 'id');
    }
}
