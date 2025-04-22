<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('leave-requests', LeaveRequestController::class)->only([
    'index', 'create', 'store', 'update', 'destroy'
]);
