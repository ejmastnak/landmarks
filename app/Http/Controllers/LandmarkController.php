<?php

namespace App\Http\Controllers;

use App\Models\Landmark;
use App\Models\Country;
use App\Models\LandmarkType;
use App\Models\LandmarkSource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class LandmarkController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $landmarks = Landmark::all(['id', 'name', 'landmark_type_id', 'city', 'country_id']);
        $landmarks->load(['country:id,name', 'landmark_type:id,name', 'landmark_sources:id,name,landmark_id']);
        $user = $request->user() ? $request->user() : null;

        return Inertia::render('Landmarks/Index', [
            'landmarks' => $landmarks,
            'countries' => Country::all(['id', 'name'])->sortBy('name')->values()->all(),
            'landmarkTypes' => LandmarkType::all(['id', 'name'])->sortBy('name')->values()->all(),
            'userCanEdit' => $user ? ($user->can('updateAny', Landmark::class)) : false,
            'userCanDelete' => $user ? ($user->can('deleteAny', Landmark::class)) : false,
            'userCanCreate' => $user ? ($user->can('create', Landmark::class)) : false,
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
        $this->validateStoreOrUpdateRequest($request);

        $country_id = $this->getCountryIDForRequest($request);
        $landmark_type_id = $this->getLandmarkTypeIDForRequest($request);

        $landmark = Landmark::create([
            'name' => $request->name,
            'landmark_type_id' => $landmark_type_id,
            'city' => $request->city,
            'country_id' => $country_id,
            'comment' => $request->comment,
            // Use admin as default user for creating landmarks
            'user_id' => $request->user() ? $request->user()->id : User::where('id', 1)->first()->id,
        ]);
        $this->updateLandmarkSources($request, $landmark);
        activity()->performedOn($landmark)->log('Landmark created');
        return Redirect::route('landmarks.index')->with('message', 'Success! Landmark created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Landmark $landmark)
    {
        $landmark->load(['country:id,name', 'landmark_type:id,name', 'landmark_sources:id,name,landmark_id']);
        $user = $request->user() ? $request->user() : null;
        return inertia('Landmarks/Show', [
            'landmark' => $landmark->only(['id', 'name', 'city', 'comment', 'country', 'landmark_type', 'landmark_sources']),
            'userCanEdit' => $user ? ($user->can('update', $landmark)) : false,
            'userCanDelete' => $user ? ($user->can('delete', $landmark)) : false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Landmark $landmark)
    {
        $landmark->load(['country:id,name', 'landmark_type:id,name', 'landmark_sources:id,name,landmark_id']);
        return inertia('Landmarks/Edit', [
            'landmark' => $landmark->only(['id', 'name', 'city', 'comment', 'country', 'landmark_type', 'landmark_sources']),
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
        $this->validateStoreOrUpdateRequest($request);

        $country_id = $this->getCountryIDForRequest($request);
        $landmark_type_id = $this->getLandmarkTypeIDForRequest($request);

        $landmark->update([
            'name' => $request->name,
            'landmark_type_id' => $landmark_type_id,
            'city' => $request->city,
            'country_id' => $country_id,
            'comment' => $request->comment
        ]);
        $this->updateLandmarkSources($request, $landmark);
        activity()->performedOn($landmark)->log('Landmark updated');
        return Redirect::route('landmarks.show', $landmark->id)->with('message', 'Success! Landmark updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Landmark  $landmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Landmark $landmark)
    {
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
        activity()->performedOn($landmark)->log('Landmark deleted');
        return Redirect::route('landmarks.index')->with('message', 'Success! Landmark deleted successfully.');
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
    private function validateStoreOrUpdateRequest(Request $request)
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
            'landmarkSources' => ['nullable', 'array', 'max:50'],
            'landmarkSources.*' => ['required', 'array:id,name'],
            'landmarkSources.*.id' => ['required', 'integer'],
            'landmarkSources.*.name' => ['required', 'string', 'min:1', 'max:250'],
        ]);
    }

    /**
     *  The request parameter contains a country object. If a matching
     *  country exists in database, returns the country's id. Otherwise
     *  creates a new country and returns the new country's id.
     */
    private function getCountryIDForRequest(Request $request)
    {
        if($request->country['id'] == -1) {
            return Country::create(['name' => $request->country['name']])->id;
        } else {
            return $request->country['id'];
        }
    }

    /**
     *  Analaog of getCountryIDForRequest for landmark types.
     */
    private function getLandmarkTypeIDForRequest(Request $request)
    {
        if($request->landmarkType['id'] === -1) {
            return LandmarkType::create(['name' => $request->landmarkType['name']])->id;
        } else {
            return $request->landmarkType['id'];
        }
    }

    /**
     *  Used to save lists of Landmarks to JSON files. The method accepts a
     *  request containing a JSON array of Landmark IDs, looks up the landmarks
     *  by ID, and returns a JSON response with relevant landmark information.
     */
    public function exportLandmarks(Request $request)
    {
        if ($request->landmarkIDs) {
            $landmarks = Landmark::findMany($request->landmarkIDs)->load('landmark_type:id,name', 'country:id,name');
            $landmarks = $landmarks->map(fn($l) => [
                'id' => $l->id,
                'name' => $l->name,
                'landmark_type' => $l->landmark_type['name'],
                'city' => $l->city,
                'country' => $l->country['name'],
                'comment' => $l->comment
            ]);
            return Response::json($landmarks)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /**
     *  Updates a the LandmarkSources associated with a given Landmark when
     *  updating or creating the Landmark.
     */
    private function updateLandmarkSources(Request $request, Landmark $landmark)
    {
        $dbLandmarkSources = $landmark->landmark_sources;
        $dbIDs = array_map(function ($dbLandmarkSource) { return $dbLandmarkSource['id']; }, $dbLandmarkSources->toArray());
        $requestLandmarkSources = $request->landmarkSources;
        $requestIDs = array_map(function ($requestLandmarkSource) { return $requestLandmarkSource['id']; }, $requestLandmarkSources);

        // Delete all LandmarkSources in `$dbLandmarkSources` and not in `$requestLandmarkSources`
        foreach ($dbLandmarkSources as $dbLandmarkSource) {
            if (!in_array($dbLandmarkSource['id'], $requestIDs)) {
                $dbLandmarkSource->delete();
            }
        }

        // Create a new LandmarkSource for any LandmarkSource in
        // `requestLandmarkSources` but not in `$dbLandmarkSources`
        foreach ($requestLandmarkSources as $requestLandmarkSource) {
            if (!in_array($requestLandmarkSource['id'], $dbIDs)) {
                LandmarkSource::create([
                  'name' => $requestLandmarkSource['name'],
                  'landmark_id' => $landmark->id,
                ]);
            }
        }

        // Update any LandmarkSources that appear in both `dbLandmarkSources`
        // and `requestLandmarkSources` so as to reflect content of
        // `requestLandmarkSources`.
        foreach ($dbLandmarkSources as $dbLandmarkSource) {
            // Is this dbLandmarkSource also in requestLandmarkSources?
            $key = array_search($dbLandmarkSource['id'], $requestIDs);
            if ($key !== false) {  // if a match was found
                $dbLandmarkSource->update([
                  'name' => $requestLandmarkSources[$key]['name'],
                  'landmark_id' => $landmark->id,
                ]);
            }
        }
    }
}
