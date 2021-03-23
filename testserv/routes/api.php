<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/signin', function(Request $request) {
    if($request->login == "test" && $request->password == "test") {
        return response()->json([
            'user' => [
                    [
                        'name' => 'tes1t',
                        'token' => '12345'
                    ]
            ]
        ]);
    } else {
        return response()->json([
            'xui' => $request->login
        ]);
    }
});
