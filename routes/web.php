<?php

use App\Http\Controllers\ImageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/images", [ImageController::class, "index"])->middleware([
    "auth",
    "verified",
]);

Route::post("/images", [ImageController::class, "store"])->middleware([
    "auth",
    "verified",
]);

Route::get("/", function () {
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
});

Route::get("/dashboard", function () {
    return Inertia::render("Dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::get("/canvas", function () {
    return Inertia::render("Canvas");
})
    ->middleware(["auth", "verified"])
    ->name("canvas");

require __DIR__ . "/auth.php";
