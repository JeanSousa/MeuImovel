<?php

use App\User;
use Illuminate\Http\Request;

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


Route::prefix('v1')->namespace('Api')->group(function (){ // api/v1
    Route::name('real_states.')->group(function (){ 
        //api/v1/states (com os prefixos fica assim)
        Route::resource('real-states', 'RealStateController'); // api/v1/states/

    });

    Route::name('users.')->group(function (){ 
        //api/v1/users (com os prefixos fica assim)
        Route::resource('users', 'UserController'); // api/v1/users/

    });

    Route::name('categories.')->group(function (){ 
        Route::get('categories/{id}/real-states', 'CategoryController@realState');
        //api/v1/categories (com os prefixos fica assim)
        Route::resource('categories', 'CategoryController'); // api/v1/categories/

    });
});


