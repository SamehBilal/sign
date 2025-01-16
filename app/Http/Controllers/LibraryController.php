<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
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

            return view('templates', compact('templates'));
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }

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

            return redirect()->route('templates.index')->with('status', 'template-updated');
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }

    public function show($id)
    {
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $client = new Client();

        try {
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/libraryDocuments/' . $id, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $templates = json_decode($response->getBody()->getContents(), true);

            return $templates;
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
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/libraryDocuments/' . $id. '/events', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $templates = json_decode($response->getBody()->getContents(), true);

            return $templates;
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
            $response = $client->get('https://secure.na4.adobesign.com/api/rest/v6/libraryDocuments/' . $id. '/combinedDocument', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            $templates = $response->getBody()->getContents();
            
            return $templates;
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('adobe.login');
            }

            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }
}
