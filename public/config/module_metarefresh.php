<?php

$config = [
    'sets' => [
        'saml_idp' => [
            'cron'       => ['hourly'],
            'sources'    => [
                [
                    // 'src' => 'https://alpe1.incubator.geant.org/simplesaml/module.php/saml/idp/metadata',
                    'src' => null,
                ],
            ],
            'expireAfter'  => 60*60*24*4, // Maximum 4 days cache time.
            'outputDir'    => 'metadata/idp',
            'outputFormat' => 'flatfile',
        ],

        'saml_sp' => [
            'cron'       => ['hourly'],
            'sources'    => [
                [
                    // 'src' => 'https://alpe2.incubator.geant.org/Shibboleth.sso/Metadata',
                    'src' => null,
                ],
            ],
            'expireAfter'  => 60*60*24*4, // Maximum 4 days cache time.
            'outputDir'    => 'metadata/sp',
            'outputFormat' => 'flatfile',
        ],


        'saml_idps' => [
            'cron'       => ['hourly'],
            'sources'    => [
                [
                    'src' => null,
                ],
            ],
            'expireAfter'  => 60*60*24*4, // Maximum 4 days cache time.
            'outputDir'    => 'metadata/idp',
            'outputFormat' => 'flatfile',
        ],

        'saml_sps' => [
            'cron'       => ['hourly'],
            'sources'    => [
                [
                    'src' => null,
                ],
            ],
            'expireAfter'  => 60*60*24*4, // Maximum 4 days cache time.
            'outputDir'    => 'metadata/sp',
            'outputFormat' => 'flatfile',
        ]
    ],
];





