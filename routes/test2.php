<?php
use Illuminate\Support\Facades\Route;
Route::get("/_test-mail", function () {
    try {
        $manager = app("mail.manager");
        $transport = $manager->createTransport(["transport" => "resend", "api_key" => "re_test"]);
        return "OK: " . get_class($transport);
    } catch (Exception $e) {
        return "Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
    }
});
