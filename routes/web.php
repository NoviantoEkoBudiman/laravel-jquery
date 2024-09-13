<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/product/render_serverside", [ProductController::class, "renderDataServerside"]);
Route::resource("product", ProductController::class)->except(["edit","create"]);