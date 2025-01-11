<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdobeSignService;

class AdobeSignController extends Controller
{
    protected $adobeSignService;

    public function __construct(AdobeSignService $adobeSignService)
    {
        $this->adobeSignService = $adobeSignService;
    }

    public function sendAgreement(Request $request)
    {
        $request->validate([
            'template_id' => 'required|string',
            'recipients' => 'required|email',
            'document_name' => 'required|string',
        ]);

        $result = $this->adobeSignService->sendAgreement(
            $request->input('template_id'),
            $request->input('recipients'),
            $request->input('document_name')
        );

        return response()->json($result);
    }

    public function sendBulkAgreements(Request $request)
    {
         $validated = $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'required|integer',
            'agreements' => 'required|string',
        ]);

        $agreements = json_decode($validated['agreements'], true);

        if (!$agreements) {
            return response()->json(['message' => 'Invalid agreements data'], 400);
        }

        // Filter agreements based on selected IDs
        /* $selectedAgreements = collect($agreements)->filter(function ($agreement, $index) use ($validated) {
            return in_array($index, $validated['selected']);
        });

        if ($selectedAgreements->isEmpty()) {
            return response()->json(['message' => 'No agreements found for the selected IDs'], 400);
        } */

        foreach ($agreements  as $agreement) {
            try {
                //return response()->json(['message' => "Sending agreement to {$agreement['email']} for {$agreement['document_name']}"], 200);

                $this->adobeSignService->sendAgreement(
                    $request->input('template_id'),
                    $agreement['email'],
                    $request->input('template_name'),
                    $agreement['data1'],
                    $agreement['data2']
                );
            } catch (\Exception $e) {
                return response()->json(['error' => "Failed to send agreement: {$e->getMessage()}"], 500);
            }
        }

        return response()->json(['message' => 'Agreements sent successfully!'], 200);
    }

    public function checkAgreementStatus($agreementId)
    {
        $status = $this->adobeSignService->getAgreementStatus($agreementId);
        return response()->json($status);
    }

    public function cancelAgreement($agreementId)
    {
        $result = $this->adobeSignService->cancelAgreement($agreementId);
        return response()->json($result);
    }
}
