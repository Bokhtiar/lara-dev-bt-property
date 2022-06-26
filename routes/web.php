<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PropertyController;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $properties = Property::query()->Active()->get(['property_id', 'image', 'title','category_id']);
    return view('user.index', compact('properties'));
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('property/list', [App\Http\Controllers\User\PropertyController::class, 'index']);
Route::get('property/detail/{id}', [App\Http\Controllers\User\PropertyController::class, 'show']);


Route::group(["as"=>'user.', "prefix"=>'user',  "middleware"=>['auth','user']],function(){
    Route::get('dashboard', [App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [App\Http\Controllers\User\UserDashboardController::class, 'logout'])->name('logout');
});

Route::group(["as"=>'admin.', "prefix"=>'admin', "middleware"=>['auth','admin']],function(){
    Route::get('dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('logout');
    Route::get('profile', [App\Http\Controllers\Admin\AdminDashboardController::class, 'profile'])->name('profile');
    /*banner */
    Route::resource('banner', BannerController::class);
    Route::get('banner/status/{id}', [BannerController::class, 'status'])->name('banner.status');
    /*category */
    Route::resource('category', CategoryController::class);
    Route::get('category/status/{id}', [CategoryController::class, 'status'])->name('category.status');
    /*Property */
    Route::resource('property', PropertyController::class);
    Route::get('property/status/{id}', [PropertyController::class, 'status'])->name('property.status');
    //location

    Route::group(["as"=>'location.',"prefix"=>'location'], function(){
        Route::get('division', [LocationController::class, 'division'])->name('division');
        Route::get('districts', [LocationController::class, 'districts'])->name('districts');
    });


});
