<?php

// app/Services/AdobeSignService.php
namespace App\Services;

use GuzzleHttp\Client;

class AdobeSignService
{
    protected $client;
    protected $baseUri = 'https://secure.na4.adobesign.com/api/rest/v6/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $this->client = new Client();
    }

    public function sendAgreement(
        $templateId,
        $recipients,
        $documentName,
        $vendor,
        $fullName,
        $bankName,
        $iban,
        $bankAddress,
        $currency,
        $swiftCode,
        $Address,
        $mobile,
        $project,
        $project1,
        $project2,
        $project3,
        $project4,
        $project5,
        $project6,
        $project7,
        $amount,
        $amount1,
        $amount2,
        $amount3,
        $amount4,
        $amount5,
        $amount6,
        $amount7,
        $passport,
        $invoice,
        $date,
        $image
    ) {
        $this->apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
        $amount  = $amount  != '' ? $amount:0;
        $amount1 = $amount1 != '' ? $amount1:0;
        $amount2 = $amount2 != '' ? $amount2:0;
        $amount3 = $amount3 != '' ? $amount3:0;
        $amount4 = $amount4 != '' ? $amount4:0;
        $amount5 = $amount5 != '' ? $amount5:0;
        $amount6 = $amount6 != '' ? $amount6:0;
        $amount7 = $amount7 != '' ? $amount7:0;
        $fullAmount = $amount + $amount1 + $amount2 + $amount3 + $amount4 + $amount5 + $amount6 + $amount7;
        $amount  = $amount  == 0 ? '':$amount;
        $amount1 = $amount1 == 0 ? '':$amount1;
        $amount2 = $amount2 == 0 ? '':$amount2;
        $amount3 = $amount3 == 0 ? '':$amount3;
        $amount4 = $amount4 == 0 ? '':$amount4;
        $amount5 = $amount5 == 0 ? '':$amount5;
        $amount6 = $amount6 == 0 ? '':$amount6;
        $amount7 = $amount7 == 0 ? '':$amount7;
        $response = $this->client->post('https://secure.na4.adobesign.com/api/rest/v6/agreements', [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ],
            /* 'json' => [
                'groupId' => 'your-group-id', // Replace with the appropriate group ID
                'locale' => 'en_US', // Set the locale as needed
                'type' => 'AGREEMENT',
                'vaultingInfo' => [
                    'enabled' => true,
                ],
                'securityOption' => [
                    'contentProtectionPreference' => [
                        'external' => 'ENABLE',
                        'internal' => 'ENABLE',
                    ],
                    'openPassword' => 'your-password', // Provide the appropriate open password
                ],
                'postSignOption' => [
                    'redirectUrl' => 'your-redirect-url', // Provide the redirect URL after signing
                    'redirectDelay' => 0,
                ],
                'notaryInfo' => [
                    'note' => 'your-note', // Provide a note if required
                    'notaryEmail' => 'notary@example.com', // Provide notary email
                    'notaryType' => 'PROVIDER_NOTARY',
                    'appointment' => 'your-appointment', // Provide appointment details if required
                    'payment' => 'BY_SENDER', // Adjust payment option as needed
                ],
                'ccs' => [
                    [
                        'visiblePages' => ['1'], // Adjust visible pages as needed
                        'label' => 'CC Label',
                        'ccSigningOrder' => ['1'],
                        'email' => 'cc@example.com', // Provide the CC recipient email
                    ]
                ],
                'senderSigns' => 'FIRST',
                'documentVisibilityEnabled' => true,
                'isDocumentRetentionApplied' => true,
                'documentRetentionAppliedDate' => '2025-01-09', // Set the retention date if required
                'hasSignerIdentityReport' => true,
                'lastEventDate' => '2025-01-09', // Set the last event date
                'senderEmail' => $recipients, // Use the sender's email
                'id' => 'your-id', // Set the ID if required
                'state' => 'AUTHORING',
                'mergeFieldInfo' => [
                    [
                        'fieldName' => 'your-field-name', // Provide the field name
                        'defaultValue' => 'your-default-value', // Provide the default value for the field
                    ]
                ],
                'firstReminderDelay' => 0,
                'agreementSettingsInfo' => [
                    'hipaaEnabled' => true,
                    'canEditFiles' => true,
                    'canEditAgreementSettings' => true,
                    'canEditElectronicSeals' => true,
                ],
                'emailOption' => [
                    'sendOptions' => [
                        'initEmails' => 'ALL',
                        'inFlightEmails' => 'ALL',
                        'completionEmails' => 'ALL',
                    ]
                ],
                'formFieldGenerators' => [
                    [
                        'formFieldNamePrefix' => 'field-prefix',
                        'participantSetName' => 'set-name',
                        'formFieldDescription' => [
                            'radioCheckType' => 'CIRCLE',
                            'borderColor' => 'black',
                            'valueExpression' => 'expression',
                            'maskingText' => 'masking-text',
                            'defaultValue' => 'default-value',
                            'masked' => true,
                            'minLength' => 0,
                            'origin' => 'AUTHORED',
                            'tooltip' => 'tooltip-text',
                            'hiddenOptions' => ['hidden-option'],
                            'required' => true,
                            'validationData' => 'validation-data',
                            'minValue' => 0.1,
                            'borderWidth' => 0.1,
                            'urlOverridable' => true,
                            'currency' => 'USD',
                            'inputType' => 'TEXT_FIELD',
                            'borderStyle' => 'SOLID',
                            'calculated' => true,
                            'contentType' => 'DATA',
                            'validation' => 'NONE',
                            'displayLabel' => 'Label',
                            'hyperlink' => [
                                'linkType' => 'INTERNAL',
                                'documentLocation' => [
                                    'pageNumber' => 1,
                                    'top' => 0.1,
                                    'left' => 0.1,
                                    'width' => 0.1,
                                    'height' => 0.1,
                                ],
                                'url' => 'your-url',
                            ],
                            'backgroundColor' => 'white',
                            'visible' => true,
                            'displayFormatType' => 'DEFAULT',
                            'maxValue' => 0.1,
                            'validationErrMsg' => 'validation-error-message',
                            'displayFormat' => 'string-format',
                            'visibleOptions' => ['option1', 'option2'],
                            'readOnly' => true,
                            'fontName' => 'Arial',
                            'conditionalAction' => [
                                'predicates' => [
                                    [
                                        'fieldName' => 'field-name',
                                        'value' => 'value',
                                        'operator' => 'EQUALS',
                                        'fieldLocationIndex' => 0,
                                    ]
                                ],
                                'anyOrAll' => 'ALL',
                                'action' => 'SHOW',
                            ],
                            'fontSize' => 0.1,
                            'alignment' => 'LEFT',
                            'fontColor' => 'black',
                            'maxLength' => 100,
                        ],
                        'generatorType' => 'ANCHOR_TEXT',
                        'linked' => true,
                    ]
                ],
                'signatureType' => 'ESIGN',
                'externalId' => [
                    'id' => 'external-id', // Provide external ID if required
                ],
                'message' => 'Agreement message', // Provide the message for the agreement
                'deviceInfo' => [
                    'deviceDescription' => 'Device Description',
                    'applicationDescription' => 'App Description',
                    'deviceTime' => '2025-01-09',
                ],
                'parentId' => 'parent-id', // Provide the parent ID if required
                'reminderFrequency' => 'DAILY_UNTIL_SIGNED',
                'redirectOptions' => [
                    [
                        'delay' => 0,
                        'action' => 'DECLINED',
                        'url' => 'decline-url',
                    ]
                ],
                'createdDate' => '2025-01-09',
                'participantSetsInfo' => [
                    [
                        'role' => 'SIGNER',
                        'visiblePages' => ['1'],
                        'providerParticipationInfo' => [
                            'participationSetId' => 'set-id',
                            'label' => 'Participant Set Label',
                            'participationId' => 'participation-id',
                        ],
                        'electronicSealId' => 'seal-id',
                        'name' => 'Signer Name',
                        'id' => 'signer-id',
                        'label' => 'Signer Label',
                        'privateMessage' => 'private-message',
                        'memberInfos' => [
                            [
                                'phoneDeliveryInfo' => [
                                    'countryIsoCode' => 'US',
                                    'phone' => '1234567890',
                                    'countryCode' => '1',
                                ],
                                'formDataLastAutoSavedTime' => '2025-01-09',
                                'name' => 'Signer Name',
                                'deliverableEmail' => true,
                                'id' => 'signer-id',
                                'isPrivate' => true,
                                'email' => 'signer@example.com',
                                'securityOption' => [
                                    'password' => 'password',
                                    'authenticationMethod' => 'NONE',
                                    'notaryAuthentication' => 'MULTI_FACTOR_AUTHENTICATION',
                                    'digAuthInfo' => [
                                        'providerId' => 'provider-id',
                                        'providerDesc' => 'provider-description',
                                        'providerName' => 'provider-name',
                                    ],
                                    'nameInfo' => [
                                        'firstName' => 'Signer First Name',
                                        'lastName' => 'Signer Last Name',
                                    ],
                                    'phoneInfo' => [
                                        'countryIsoCode' => 'US',
                                        'phone' => '1234567890',
                                        'countryCode' => '1',
                                    ],
                                    'identityCheckInfo' => [
                                        'emailMatch' => [
                                            'allowCustomAlternateEmail' => true,
                                            'allowRegisteredAlternateEmail' => true,
                                            'alternateEmails' => ['alt@example.com'],
                                            'requireEmailMatching' => true,
                                        ],
                                        'nameMatch' => [
                                            'nameMatchCriteria' => 'DISABLED',
                                        ],
                                    ],
                                ],
                            ]
                        ],
                        'order' => 0,
                    ]
                ],
                'hasFormFieldData' => true,
                'expirationTime' => '2025-01-09',
                'formFieldLayerTemplates' => [
                    [
                        'notarize' => true,
                        'transientDocumentId' => 'transient-doc-id',
                        'document' => [
                            'numPages' => 1,
                            'createdDate' => '2025-01-09',
                            'name' => 'Document Name',
                            'id' => 'document-id',
                            'label' => 'Document Label',
                            'mimeType' => 'application/pdf',
                        ],
                        'libraryDocumentId' => 'library-doc-id',
                        'label' => 'Document Label',
                        'urlFileInfo' => [
                            'name' => 'document-name',
                            'mimeType' => 'application/pdf',
                            'url' => 'document-url',
                        ]
                    ]
                ],
                'name' => $documentName,
                'sendType' => 'FILL_SIGN',
                'fileInfos' => [
                    [
                        'notarize' => true,
                        'transientDocumentId' => 'transient-doc-id',
                        'document' => [
                            'numPages' => 1,
                            'createdDate' => '2025-01-09',
                            'name' => 'Document Name',
                            'id' => 'document-id',
                            'label' => 'Document Label',
                            'mimeType' => 'application/pdf',
                        ],
                        'libraryDocumentId' => 'library-doc-id',
                        'label' => 'Document Label',
                        'urlFileInfo' => [
                            'name' => 'document-name',
                            'mimeType' => 'application/pdf',
                            'url' => 'document-url',
                        ]
                    ]
                ],
                'createdGroupId' => 'group-id',
                'workflowId' => 'workflow-id',
                'status' => 'OUT_FOR_SIGNATURE',
            ], */
            'json' => [
                "signatureFlow" => "SENDER_SIGNATURE_NOT_REQUIRED",
                'participantSetsInfo' => [
                    [
                        'role' => 'SIGNER',
                        'order' => 1,
                        'memberInfos' => [
                            [
                                'email' => $recipients,
                            ],
                        ],
                    ],
                ],
                'name' => $documentName,
                'signatureType' => 'ESIGN',
                'fileInfos' => [
                    [
                        'libraryDocumentId' => $templateId,
                    ],
                ],
                'mergeFieldInfo' => [
                    [
                        'fieldName' => 'vendor',
                        'defaultValue' => $vendor,
                    ],
                    [
                        'fieldName' => 'full_name',
                        'defaultValue' => $fullName,
                    ],
                    [
                        'fieldName' => 'full_name1',
                        'defaultValue' => $fullName,
                    ],
                    [
                        'fieldName' => 'bank_name',
                        'defaultValue' => $bankName,
                    ],
                    [
                        'fieldName' => 'iban',
                        'defaultValue' => $iban,
                    ],
                    [
                        'fieldName' => 'bank_address',
                        'defaultValue' => $bankAddress,
                    ],
                    [
                        'fieldName' => 'currency',
                        'defaultValue' => $currency,
                    ],
                    [
                        'fieldName' => 'swift_code',
                        'defaultValue' => $swiftCode,
                    ],
                    [
                        'fieldName' => 'address',
                        'defaultValue' => $Address,
                    ],
                    [
                        'fieldName' => 'mobile',
                        'defaultValue' => $mobile,
                    ],
                    [
                        'fieldName' => 'project',
                        'defaultValue' => $project,
                    ],
                    [
                        'fieldName' => 'project1',
                        'defaultValue' => $project1,
                    ],
                    [
                        'fieldName' => 'project2',
                        'defaultValue' => $project2,
                    ],
                    [
                        'fieldName' => 'project3',
                        'defaultValue' => $project3,
                    ],
                    [
                        'fieldName' => 'project4',
                        'defaultValue' => $project4,
                    ],
                    [
                        'fieldName' => 'project5',
                        'defaultValue' => $project5,
                    ],
                    [
                        'fieldName' => 'project6',
                        'defaultValue' => $project6,
                    ],
                    [
                        'fieldName' => 'project7',
                        'defaultValue' => $project7,
                    ],
                    [
                        'fieldName' => 'amount',
                        'defaultValue' => $amount,
                    ],
                    [
                        'fieldName' => 'amount1',
                        'defaultValue' => $amount1,
                    ],
                    [
                        'fieldName' => 'amount2',
                        'defaultValue' => $amount2,
                    ],
                    [
                        'fieldName' => 'amount3',
                        'defaultValue' => $amount3,
                    ],
                    [
                        'fieldName' => 'amount4',
                        'defaultValue' => $amount4,
                    ],
                    [
                        'fieldName' => 'amount5',
                        'defaultValue' => $amount5,
                    ],
                    [
                        'fieldName' => 'amount6',
                        'defaultValue' => $amount6,
                    ],
                    [
                        'fieldName' => 'amount7',
                        'defaultValue' => $amount7,
                    ],
                    [
                        'fieldName' => 'total_amount',
                        'defaultValue' => $fullAmount,
                    ],
                    [
                        'fieldName' => 'passport',
                        'defaultValue' => $passport,
                    ],
                    [
                        'fieldName' => 'invoice',
                        'defaultValue' => $invoice,
                    ],
                    [
                        'fieldName' => 'date',
                        'defaultValue' => $date,
                    ],
                    [
                        'fieldName' => 'image',
                        'defaultValue' => $image,
                    ],
                    [
                        'fieldName' => 'email',
                        'defaultValue' => $recipients,
                    ],
                ],
                'state' => 'IN_PROCESS',
                'status' => "OUT_FOR_SIGNATURE"
            ],
        ]);

        //return json_decode($response->getBody()->getContents(), true);
    }

    public function getAgreementStatus($agreementId)
    {
        $response = $this->client->get("/agreements/{$agreementId}");
        return json_decode($response->getBody()->getContents(), true);
    }
}
