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

        foreach ($agreements  as $agreement) {
            try {
                $this->adobeSignService->sendAgreement(
                    $request->input('template_id'),
                    $agreement['email'],
                    $request->input('template_name'),
                    $agreement['vendor'],
                    $agreement['full_name'],
                    $agreement['bank_name'],
                    $agreement['iban_aed'],
                    $agreement['iban_usd'],
                    $agreement['iban_eur'],
                    $agreement['swift_code'],
                    $agreement['address'],
                    $agreement['telephone'],
                    $agreement['project'],
                    $agreement['full_amount'],
                );
            } catch (\Exception $e) {
                return response()->json(['error' => "Failed to send agreement: {$e->getMessage()}"], 500);
            }
        }

        return redirect()->back()->with('status', 'Agreements sent successfully!');
    }

    public function checkAgreementStatus($agreementId)
    {
        $status = $this->adobeSignService->getAgreementStatus($agreementId);
        return response()->json($status);
    }
}
