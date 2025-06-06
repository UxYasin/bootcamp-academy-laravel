<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ModalController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\HomeController;

//Cache clear route
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Cache::flush();

    return 'Application cache cleared';
});

Route::get('home/switch/{id}', [HomeController::class, 'homepage_switcher'])->name('home.switch');

//Redirect route
Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect(route('admin.dashboard'));
    }elseif(auth()->user()->role == 'student'){
        return redirect(route('my.courses'));
    } else {
        return redirect(route('home'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

//Common modal route
Route::get('modal/{view_path}', [ModalController::class, 'common_view_function'])->name('modal');
Route::any('get-video-details/{url?}', [CommonController::class, 'get_video_details'])->name('get.video.details');
Route::get('view/{path}', [CommonController::class, 'rendered_view'])->name('view');

Route::get('closed_back_to_mobile_ber', function () {
    session()->forget('app_url');
    return redirect()->back();
})->name('closed_back_to_mobile_ber');

//Installation routes
Route::controller(InstallController::class)->group(function () {
    Route::get('/install_ended', 'index');
});
//Installation routes