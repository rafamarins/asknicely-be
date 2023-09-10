<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Hacky way to get CORS working locally with custom domains
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET, POST, PATCH');
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Headers:X-Requested-With, Content-Type, withCredentials');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};