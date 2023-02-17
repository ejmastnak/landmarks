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

    /**
     *  Returns an array of all landmark types with an "All" type appended.
     *  This list is meant to be used only for filtering landmarks by type,
     *  where an "All" option is useful.
     */
    public static function landmarkTypesForFiltering()
    {
        return array_merge([["id" => 0, "name" => "All"]], LandmarkType::all(['id', 'name'])->toArray());
    }

}
