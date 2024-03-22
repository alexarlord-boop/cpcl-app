<?php

$config = [
    'sets' => [
        'saml_idp' => [
            'cron'       => ['hourly'],
            'sources'    => [
                [
                    'src' => 'https://alpe1.incubator.geant.org/simplesaml/module.php/saml/idp/metadata',
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
                    'src' => 'https://alpe2.incubator.geant.org/Shibboleth.sso/Metadata',
                ],
            ],
            'expireAfter'  => 60*60*24*4, // Maximum 4 days cache time.
            'outputDir'    => 'metadata/sp',
            'outputFormat' => 'flatfile',
        ],
    ],
];





