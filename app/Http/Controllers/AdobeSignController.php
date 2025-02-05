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
                    $agreement['iban'],
                    $agreement['bank_address'],
                    $agreement['currency'],
                    $agreement['swift_code'],
                    $agreement['address'],
                    $agreement['mobile'],
                    $agreement['project'],
                    $agreement['project1'],
                    $agreement['project2'],
                    $agreement['project3'],
                    $agreement['project4'],
                    $agreement['project5'],
                    $agreement['project6'],
                    $agreement['project7'],
                    $agreement['amount'],
                    $agreement['amount1'],
                    $agreement['amount2'],
                    $agreement['amount3'],
                    $agreement['amount4'],
                    $agreement['amount5'],
                    $agreement['amount6'],
                    $agreement['amount7'],
                    $agreement['passport'],
                    $agreement['invoice'],
                    $agreement['date'],
                    $agreement['image'],
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
