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
            'countries' => Country::all(['id', 'name']),
            'landmarkTypes' => LandmarkType::all(['id', 'name'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Landmarks/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:1', 'max:100'],
            'type' => ['required', 'min:1', 'max:100'],
            'city' => ['required', 'min:1', 'max:100'],
            'country' => ['required', 'min:1', 'max:100'],
            'comment' => ['nullable', 'max:1000']
        ]);

        Landmark::create([
            'name' => $request->name,
            'type' => $request->type,
            'city' => $request->city,
            'country' => $request->country,
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
    public function edit(Landmark $landmark)
    {
        return inertia('Landmarks/Edit', [
            'landmark' => $landmark,
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
        $request->validate([
            'name' => ['required', 'min:1', 'max:100'],
            'type' => ['required', 'min:1', 'max:100'],
            'city' => ['required', 'min:1', 'max:100'],
            'country' => ['required', 'min:1', 'max:100'],
            'comment' => ['nullable', 'max:1000']
        ]);

        $landmark->update([
            'name' => $request->name,
            'type' => $request->type,
            'city' => $request->city,
            'country' => $request->country,
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
            $landmark->delete();
        }
        return Redirect::route('landmarks.index');
    }
}
