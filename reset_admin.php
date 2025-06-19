<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Resetting admin password...\n";

    $admin = App\Models\Admin::where('email', 'admin@example.com')->first();

    if ($admin) {
        $admin->password = bcrypt('admin123');
        $admin->save();

        echo "Admin password reset successfully!\n";
        echo "Email: admin@example.com\n";
        echo "Password: admin123\n";
    } else {
        echo "Admin user not found!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
