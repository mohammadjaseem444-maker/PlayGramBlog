<?php
declare(strict_types=1);
session_start();

$custom = __DIR__ . '/config.php';
if (file_exists($custom)) {
    $config = require $custom;
} else {
    $config = [
        'db_host' => getenv('PB_DB_HOST') ?: 'localhost',
        'db_name' => getenv('PB_DB_NAME') ?: 'playgramblog',
        'db_user' => getenv('PB_DB_USER') ?: '',
        'db_pass' => getenv('PB_DB_PASS') ?: '',
        'site_url' => getenv('PB_SITE_URL') ?: '',
        'app_env' => getenv('PB_APP_ENV') ?: 'production'
    ];
}
date_default_timezone_set('Asia/Kolkata');
error_reporting($config['app_env'] === 'development' ? E_ALL : 0);
ini_set('display_errors', $config['app_env'] === 'development' ? '1' : '0');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
