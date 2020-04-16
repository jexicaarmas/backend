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


Route::group(['middleware' => 'apiauth'], function () {
  /****** Rutas públicas  ****/
  Route::name('login')->post('login', ['uses' => 'AuthController@signIn']);
    /****** Rutas con autenticación  ****/
    Route::group(['middleware' => ['auth:api']], function () {
      /****** Rutas con nombre  ****/
      Route::name('logout')->post('logout', ['uses' => 'AuthController@signOut']); 

      /****** Rutas con CRUD  ****/
      Route::resource('products', 'ProductController',['only' => ['index', 'store', 'show', 'update', 'search']]);
            
      });
  });

