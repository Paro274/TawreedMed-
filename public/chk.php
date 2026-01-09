<?php
echo "cwd: ".getcwd()."\n";
echo "DOCUMENT_ROOT: ".($_SERVER['DOCUMENT_ROOT'] ?? 'N/A')."\n";
echo "include_path: ".get_include_path()."\n";
echo "server.php exists (relative): ";
var_export(file_exists(__DIR__ . '/../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'));
echo "\n";
echo "server.php exists (absolute): ";
var_export(file_exists('C:\xampp\htdocs\toreed2\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php'));
echo "\n";
phpinfo();
