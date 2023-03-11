<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'landmark_type_id', 'city', 'country_id', 'comment', 'link', 'user_id'];

    public function landmark_type() 
    {
      return $this->belongsTo(LandmarkType::class, 'landmark_type_id', 'id');
    }

    public function country() 
    {
      return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function landmark_sources()
    {
        return $this->hasMany(LandmarkSource::class, 'landmark_id', 'id');
    }
}
