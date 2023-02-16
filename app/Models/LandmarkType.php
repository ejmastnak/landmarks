<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandmarkType extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function landmarks()
    {
        return $this->hasMany(Landmark::class, 'landmark_type_id', 'id');
    }
}
