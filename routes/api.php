<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/user/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
Route::post('/user/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $token =  $user->createToken($request->email)->plainTextToken;
    return ['user' => $user, 'token' => $token];
});

Route::middleware('auth:sanctum')->prefix('v1')->group( function() {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    // get all holidays
    Route::get('/holidays', [\App\Http\Controllers\Api\HolidayController::class, 'index']);

    // insert holiday
    Route::post('/holiday', [\App\Http\Controllers\Api\HolidayController::class, 'create']);

    // get holiday by id
    Route::get('/holiday/{id}', [\App\Http\Controllers\Api\HolidayController::class, 'read']);

    // update holiday
    Route::put('/holiday/{id}', [\App\Http\Controllers\Api\HolidayController::class, 'update']);

    // delete holiday
    Route::delete('/holiday/{id}', [\App\Http\Controllers\Api\HolidayController::class, 'delete']);
});
