<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = new \App\Services\RajaOngkirService();
$result = $service->getProvinces();

echo "=== PROVINCE TEST ===\n";
echo json_encode($result, JSON_PRETTY_PRINT);
echo "\n\n";

if ($result['success'] && isset($result['data'][0])) {
    $provinceId = $result['data'][0]['id'];
    echo "=== CITY TEST (Province ID: {$provinceId}) ===\n";
    $cities = $service->getCities($provinceId);
    echo json_encode($cities, JSON_PRETTY_PRINT);
}
