<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Http\Requests\StoreAgreementRequest;
use App\Http\Requests\UpdateAgreementRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;

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

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/agreements', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $agreements = json_decode($response->getBody()->getContents(), true);

            return view('agreements', compact('agreements'));
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login'); 
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string',
            'id'    => 'required|string'
        ]);

        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();
        $response = $client->post('https://secure.na4.adobesign.com/api/rest/v6/libraryDocuments', [
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
            ],
            'json' => [
                "creatorEmail" => Auth::user()->email,
                "creatorName" => Auth::user()->name,
                "sharingMode" => "USER",
                "ownerEmail" => Auth::user()->email,
                "ownerName" => Auth::user()->name,
                "templateTypes" => [
                    "DOCUMENT"
                ],
                "name" => $request->name,
                "fileInfos" => [
                    [
                        "notarize" => true,
                        "transientDocumentId" => $request->id,
                    ]
                ],
                "state" => "AUTHORING",
                "status" => "AUTHORING",
                "isDocumentRetentionApplied" => true,  
                "createdDate" => now()->toDateString(), 
                "modifiedDate" => now()->toDateString(), 
                "lastEventDate" => now()->toDateString(), 
            ]
        ]);

        $response_id = json_decode($response->getBody()->getContents(), true);

        $response = $client->post('https://secure.na4.adobesign.com/api/rest/v6/agreements', [
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'participantSetsInfo' => [
                    [
                        'role' => 'SIGNER',
                        'order' => 1,
                        "deliverableEmail" => true,
                        'memberInfos' => [
                            [
                                'email' => $request->email,
                            ],
                        ],
                    ],
                ],
                'name' => $request->name,
                'signatureType' => 'ESIGN',
                'fileInfos' => [
                    [
                        'libraryDocumentId' => $response_id['id'],
                    ],
                ],
                'state' => 'IN_PROCESS',
            ],
        ]);

        return redirect()->route('agreements.index')->with('status','template-updated');
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
