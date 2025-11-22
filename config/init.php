<?php
// Initialize data directory and JSON files
$dataDir = __DIR__ . '/../data';

if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$files = [
    'users.json' => [],
    'courses.json' => [],
    'requests.json' => [],
    'enrollments.json' => []
];

foreach ($files as $filename => $defaultData) {
    $filepath = $dataDir . '/' . $filename;
    if (!file_exists($filepath)) {
        file_put_contents($filepath, json_encode($defaultData, JSON_PRETTY_PRINT));
    }
}

//new