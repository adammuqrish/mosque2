<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing email verification notification...\n";

$user = \App\Models\User::where('email', 'hassan.312@example.com')->first();
if (!$user) {
    echo "User not found. Creating test user...\n";
    $user = \App\Models\User::create([
        'name' => 'Hassan Test',
        'email' => 'hassan.312@example.com',
        'password' => bcrypt('password'),
        'phone' => '0123456789',
        'role' => 'member',
    ]);
}

echo "User: " . $user->name . " (" . $user->email . ")\n";
echo "Verified: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";

echo "Sending verification email...\n";
try {
    $user->sendEmailVerificationNotification();
    echo "Email sent successfully!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
