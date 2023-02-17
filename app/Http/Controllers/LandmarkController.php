<?php

namespace App\Http\Controllers;

use App\Models\Landmark;
use App\Models\Country;
use App\Models\LandmarkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LandmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $landmarks = Landmark::all(['id', 'name', 'landmark_type_id', 'city', 'country_id', 'link']);
        $landmarks->load(['country:id,name', 'landmark_type:id,name']);
        return Inertia::render('Landmarks/Index', [
            'landmarks' => $landmarks,
            'filterCountries' => Country::countriesForFiltering(),
            'filterLandmarkTypes' => LandmarkType::landmarkTypesForFiltering(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Landmarks/Create', [
            'countries' => Country::all(['id', 'name']),
            'landmarkTypes' => LandmarkType::all(['id', 'name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateCreateOrUpdateRequest($request);

        $country_id = $this->getCountryIDForRequest($request);
        $landmark_type_id = $this->getLandmarkTypeIDForRequest($request);

        Landmark::create([
            'name' => $request->name,
            'landmark_type_id' => $landmark_type_id,
            'city' => $request->city,
            'country_id' => $country_id,
            'comment' => $request->comment
        ]);

        return Redirect::route('landmarks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function show(Landmark $landmark)
    {
        return inertia('Landmarks/Show', [
            'landmark' => $landmark,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $landmark = Landmark::query()
        ->get(['id', 'name', 'landmark_type_id', 'city', 'country_id', 'link'])
        ->find($id)
        ->load(['country:id,name', 'landmark_type:id,name']);
        return inertia('Landmarks/Edit', [
            'landmark' => $landmark,
            'countries' => Country::all(['id', 'name']),
            'landmarkTypes' => LandmarkType::all(['id', 'name'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Landmark $landmark)
    {
        $this->validateCreateOrUpdateRequest($request);

        $country_id = $this->getCountryIDForRequest($request);
        $landmark_type_id = $this->getLandmarkTypeIDForRequest($request);

        $landmark->update([
            'name' => $request->name,
            'landmark_type_id' => $landmark_type_id,
            'city' => $request->city,
            'country_id' => $country_id,
            'comment' => $request->comment
        ]);
        return Redirect::route('landmarks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $landmark = Landmark::find($id);
        if ($landmark) {
            $country_id = $landmark->country_id;
            $landmark_type_id = $landmark->landmark_type_id;
            $landmark->delete();
            // Also delete the landmark's country and/or landmark type if there
            // are no remaining landmarks with this country/type.
            if(Landmark::where('country_id', $country_id)->doesntExist()) {
                Country::find($country_id)->delete();
            }
            if(Landmark::where('landmark_type_id', $landmark_type_id)->doesntExist()) {
                LandmarkType::find($landmark_type_id)->delete();
            }
        }
        return Redirect::route('landmarks.index');
    }

    /**
     *  Validates an incoming store or update request.
     *  Request is expected to contain:
     *  name: string
     *  city: string
     *  comments: string|null
     *  type: Object
     *      type.id: integer (-1 or matches ID of an existing LandmarkType)
     *      type.name: string (used only if type.id == -1)
     *  country: Object
     *      country.id: integer (-1 or matches ID of an existing Country)
     *      country.name: string (used only if country.id == -1)
     */
    private function validateCreateOrUpdateRequest(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:1', 'max:100'],
            'city' => ['required', 'min:1', 'max:100'],
            'comment' => ['nullable', 'max:10000'],
            'country' => ['required', 'array:id,name'],
            'country.name' => ['required', 'string', 'min:1', 'max:100'],
            'country.id' => [
                'integer',
                function ($attribute, $value, $fail) {
                    // Does a Country record with this id exist in database?
                    $exists = Country::where('id', $value)->exists();
                    if (($value !== -1) && (!$exists)) {
                        $fail('Error processing country.');
                    }
                },
            ],
            'landmarkType' => ['required', 'array:id,name'],
            'landmarkType.name' => ['required', 'string', 'min:1', 'max:100'],
            'landmarkType.id' => [
                'integer',
                function ($attribute, $value, $fail) {
                    // Does a LandmarkType record with this id exist in database?
                    $exists = LandmarkType::where('id', $value)->exists();
                    if (($value !== -1) && (!$exists)) {
                        $fail('Error processing landmark type.');
                    }
                },
            ],
        ]);
    }

    /**
     *  The request contains a country object.
     *  If a matching country exists in database, returns the country's id.
     *  Otherwise creates a new country and returns the new country's id.
     */
    private function getCountryIDForRequest($request) {
        if($request->country['id'] == -1) {
            return Country::create(['name' => $request->country['name']])->id;
        } else {
            return $request->country['id'];
        }
    }

    /**
     *  Analaog of getCountryIDForRequest for landmark types.
     */
    private function getLandmarkTypeIDForRequest($request) {
        if($request->landmarkType['id'] === -1) {
            return LandmarkType::create(['name' => $request->landmarkType['name']])->id;
        } else {
            return $request->landmarkType['id'];
        }
    }
}
