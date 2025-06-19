<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Checking admin users...\n";

    $admins = App\Models\Admin::all();
    echo "Found " . count($admins) . " admin users:\n";

    foreach ($admins as $admin) {
        echo "- ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}, Status: {$admin->status}\n";
    }

    if (count($admins) === 0) {
        echo "\nNo admin users found. Creating a test admin...\n";

        $admin = App\Models\Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        echo "Created admin user: {$admin->email} with password: password123\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
