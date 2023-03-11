<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandmarkSource extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'landmark_id'];

    public function landmark()
    {
        return $this->belongsTo(Landmark::class, 'landmark_id', 'id');
    }
}
