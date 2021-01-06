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

                     //namespace é para controlller
Route::prefix('v1')->namespace('Api')->group(function (){ // api/v1

    Route::post('login', 'Auth\\LoginJwtController@login')->name('login');

    Route::get('logout', 'Auth\\LoginJwtController@logout')->name('logout');

    Route::get('refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');

    Route::get('/search', 'RealStateSeachController@index')->name('search');


    //todas as rotas abaixo estão sob o middleware jwt.auth
    Route::group(['middleware' => ['jwt.auth']], function(){

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
    
        Route::name('photos.')->prefix('photos')->group(function (){
           Route::delete('/{id}', 'RealStatePhotoController@remove')->name('delete');
    
           Route::put('/set-thumb/{photoId}/{realState}', 'RealStatePhotoController@setThumb')->name('delete');
        });

    });
   
});


