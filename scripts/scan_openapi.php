<?php
require __DIR__ . '/../vendor/autoload.php';

// Files or directories to scan individually for debugging
$targets = [
    __DIR__ . '/../app/Http/Controllers/Controller.php',
    __DIR__ . '/../routes/swagger.php',
    __DIR__ . '/../app/OpenApi/OpenApi.php',
    __DIR__ . '/../app',
    __DIR__ . '/../routes',
];

try {
    $generator = new \OpenApi\Generator();
    $aggregate = [];
    foreach ($targets as $target) {
        echo "Scanning: $target\n";
        $openapi = $generator->generate([$target], null, false);
        $json = $openapi ? $openapi->toJson() : '';
        $found = (strpos($json, '"info"') !== false) ? 'yes' : 'no';
        echo "  Found @OA Info in this target? $found\n";
        $aggregate[$target] = $found;
    }
    file_put_contents(__DIR__ . '/openapi_debug.json', json_encode($aggregate, JSON_PRETTY_PRINT));
    echo "Saved per-target debug results to scripts/openapi_debug.json\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
