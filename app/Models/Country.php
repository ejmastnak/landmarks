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

    /**
     *  Returns an array of all countries with an "All" country appended.
     *  This list is meant to be used only for filtering landmarks by country,
     *  where an "All" option is useful.
     */
    public static function countriesForFiltering()
    {
        return array_merge([["id" => 0, "name" => "All"]], Country::all(['id', 'name'])->toArray());
    }

}
