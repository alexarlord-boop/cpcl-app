<?php
namespace App;

class Constants
{
    const XML_METADATA_DIR = 'metadata/';
    const UPLOAD_DIR = 'uploads/';
    const METAREFRESH_PATH = '/var/www/laravel-app/public/config/module_metarefresh.php';
    const OUTPUT_DIR = 'metadata/shared/';  // relatively to simplesamlphp/
    const IDP_DIR = 'metadata/idp';
    const SP_DIR = 'metadata/sp';
    const HOUR = 60;

    const ENTITY_CACHE_LIVE = Constants::HOUR * 24;

}
