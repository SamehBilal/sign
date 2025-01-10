<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Http\Requests\StoreAgreementRequest;
use App\Http\Requests\UpdateAgreementRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AgreementController extends Controller
{
    public function prepareAgreements(Request $request)
    {
        $request->validate(['talent_ids' => 'required|array']);

        $agreements = [];
        foreach ($request->talent_ids as $talentId) {
            $agreements[] = Agreement::create([
                'talent_id' => $talentId,
                'template_id' => 'TEMPLATE_ID', // Replace with actual template ID logic
            ]);
        }

        return response()->json(['agreements' => $agreements]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();
        $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/agreements', [
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ],
        ]);

        $agreements = json_decode($response->getBody()->getContents(), true);

        return view('index',compact('agreements'));
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
    public function store(StoreAgreementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Agreement $agreement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agreement $agreement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgreementRequest $request, Agreement $agreement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agreement $agreement)
    {
        //
    }
}
