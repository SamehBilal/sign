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

    public function sendAgreement($templateId, $recipients, $documentName, $data1, $data2)
    {
        $this->apiKey = session()->get('ADOBESIGN_ACCESS_TOKEN');
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
                "signatureFlow"=> "SENDER_SIGNATURE_NOT_REQUIRED",
                'participantSetsInfo' => [
                    [
                        'role' => 'APPROVER',
                        'order' => 2,
                        'memberInfos' => [
                            [
                                'email' => 'sameh@ar-ad.com',
                            ],
                        ],
                    ],
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
                        'fieldName' => 'data1',
                        'defaultValue' => $data1,
                    ],
                    [
                        'fieldName' => 'data2',
                        'defaultValue' => $data2,
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

    // Cancel Agreement
    public function cancelAgreement($agreementId)
    {
        $response = $this->client->put("/agreements/{$agreementId}/state", [
            'json' => [
                'state' => 'CANCELLED',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
