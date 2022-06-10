<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// For Login, Logout, Forgot password

Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth.api')->post('logout',[LoginController::class,'logout']);
Route::post('password/email', [LoginController::class,'forgot']);
Route::post('password/reset', [LoginController::class,'reset']);

// category API

Route::any('category-list', [CategoryController::class, 'index']);    
Route::any('category-create', [CategoryController::class, 'store']);        
Route::any('get-category-by-id/{id}', [CategoryController::class, 'getCategoryById']);        
Route::any('category-update/{category}', [CategoryController::class, 'update']);    
Route::any('category-destroy/{category}', [CategoryController::class, 'destroy']);    


//  Product Api

Route::any('product-list', [ProductController::class, 'index']);    
Route::any('product-create', [ProductController::class, 'store']);        
Route::any('get-product-by-id/{id}', [ProductController::class, 'getProductById']);        
Route::any('product-update/{product}', [ProductController::class, 'update']);    
Route::any('product-destroy/{product}', [ProductController::class, 'destroy']); 
   






