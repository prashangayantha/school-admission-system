<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Document;

echo "=== DOCUMENT SYSTEM TEST ===\n";
echo "Table exists: " . (Schema::hasTable('documents') ? "YES" : "NO") . "\n";
echo "Model loaded: " . (class_exists('App\Models\Document') ? "YES" : "NO") . "\n";

if (class_exists('App\Models\Document')) {
    try {
        $count = Document::count();
        echo "Documents count: " . $count . "\n";
        echo "🎉 SYSTEM READY!\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
echo "============================\n";