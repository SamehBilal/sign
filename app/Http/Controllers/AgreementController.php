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
    public function dashboard()
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/libraryDocuments', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $templates = json_decode($response->getBody()->getContents(), true);

            return view('dashboard', compact('templates'));
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
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
        try {
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

            return redirect()->route('agreements.index')->with('status', 'template-updated');
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/agreements/' . $id, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $agreements = json_decode($response->getBody()->getContents(), true);

            return $agreements;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Show the events the specified resource.
     */
    public function events($id)
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/agreements/' . $id. '/events', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $agreements = json_decode($response->getBody()->getContents(), true);

            return $agreements;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }

    public function file($id)
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/agreements/' . $id. '/combinedDocument', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $agreements = $response->getBody()->getContents();

            return $agreements;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
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
