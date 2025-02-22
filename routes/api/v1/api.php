<?php

use App\Http\Controllers\API\BrandController;
use Illuminate\Support\Facades\Route;


//todo group یادت نره :)
Route::get('brands', [BrandController::class, 'index'])->middleware('throttle:60,1');
