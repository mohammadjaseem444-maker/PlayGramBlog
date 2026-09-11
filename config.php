<?php
/* Copy config.example.php to config.php and set real hosting credentials. */
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    die('Please create config/config.php from config.example.php.');
}
