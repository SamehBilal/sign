<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talent;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt,xlsx,xls']);

        $file = $request->file('file')->store('uploads');
        $csvData = array_map('str_getcsv', file(Storage::path($file)));
        $talents = [];

        foreach ($csvData as $index => $row) {
            if ($index === 0) continue; // Skip header
            $talents[] = [
                'name' => $row[0],
                'email' => $row[1],
                'bank_account' => $row[2],
                'document_name' => $row[3]
            ];
            /* Talent::updateOrCreate(
                ['email' => $row[1]], // Assuming email is unique
                [
                    'name' => $row[0],
                    'bank_account' => $row[2],
                ]
            ); */
        }
        return response()->json(['results' => $talents]);
        //return response()->json(['message' => 'File uploaded and parsed successfully']);
    }
}
