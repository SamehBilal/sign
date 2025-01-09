<?php

namespace App\Http\Controllers;

use App\Models\Talent;
use App\Http\Requests\StoreTalentRequest;
use App\Http\Requests\UpdateTalentRequest;

class TalentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTalentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Talent $talent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Talent $talent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTalentRequest $request, Talent $talent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Talent $talent)
    {
        //
    }
}
