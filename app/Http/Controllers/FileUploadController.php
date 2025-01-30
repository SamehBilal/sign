<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class FileUploadController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt,xlsx,xls']);

        $file = $request->file('file')->store('uploads');
        $csvData = array_map('str_getcsv', file(Storage::path($file)));
        $talents = [];

        foreach ($csvData as $index => $row) {
            if ($index === 0) continue;
            $talents[] = [
                'vendor'        => $row[0],
                'email'         => $row[1],
                'full_name'     => $row[2],
                'bank_name'     => $row[3],
                'iban'          => $row[4],
                'bank_address'  => $row[5],
                'currency'      => $row[6],
                'swift_code'    => $row[7],
                'address'       => $row[8],
                'mobile'        => $row[9],
                'project'       => $row[10],
                'total_amount'  => $row[11],
                'passport'      => $row[12],
                'invoice'       => $row[13],
                'date'          => $row[14],
            ];
        }
        return response()->json(['results' => $talents]);
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate(['file' => 'required|mimes:pdf,doc,docx']);
        $file = $request->file('file');
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');

        if (!$file) {
            return back()->withErrors(['file' => 'No file uploaded.']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
            ])->attach('File', file_get_contents($file), $file->getClientOriginalName())
                ->post('https://secure.na4.adobesign.com/api/rest/v6/transientDocuments', $request->all());

            $responseData = $response->json();
            if (array_key_exists('transientDocumentId', $responseData)) {
                $responseData['transientDocumentId'];

                return response()->json([
                    'results' => [
                        'id' => $responseData['transientDocumentId'],
                        'name' => $file->getClientOriginalName()
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => "Failed to upload the document: {$e->getMessage()}"], 500);
        }
    }

    public function uploadAgreement(Request $request){
        $request->validate(['file' => 'required|mimes:pdf,doc,docx']);
        $file = $request->file('file');
        $apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');

        if (!$file) {
            return back()->withErrors(['file' => 'No file uploaded.']);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
            ])->attach('File', file_get_contents($file), $file->getClientOriginalName())
                ->post('https://secure.na4.adobesign.com/api/rest/v6/transientDocuments', $request->all());

            $responseData = $response->json();
            if (array_key_exists('transientDocumentId', $responseData)) {
                $responseData['transientDocumentId'];

                return response()->json([
                    'results' => [
                        'id' => $responseData['transientDocumentId'],
                        'name' => $file->getClientOriginalName()
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => "Failed to upload the document: {$e->getMessage()}"], 500);
        }
    }
}
