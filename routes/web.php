<?php

use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/store-login', [LoginController::class, 'login'])->name('store-login');
Route::get('/logout', [LoginController::class, 'logout'])->name('sign-out');
Route::get('/sign-up', [LoginController::class, 'showRegisterForm'])->name('sign-up');
Route::any('/sign-up-user', [LoginController::class, 'register'])->name('sign-up-user');

Auth::routes();

// Admin Routed
Route::group(['prefix' => 'admin'], function(){

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');

    Route::get('/user/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');

    Route::post('/user/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');

    Route::get('/chat-groups', [App\Http\Controllers\Admin\ChatGroupController::class, 'index'])->name('admin.chat-groups');

    Route::get('/chat-groups/create/{id?}', [App\Http\Controllers\Admin\ChatGroupController::class, 'groupCreate'])->name('admin.chat-groups-create');//Renu

    Route::get('/chat-groups/del/{id?}', [App\Http\Controllers\Admin\ChatGroupController::class, 'groupDel'])->name('admin.chat-groups-del');//Renu

    Route::post('/chat-groups/exist', [App\Http\Controllers\Admin\ChatGroupController::class, 'formValid'])->name('admin.chat-groups-exist'); //Renu

    Route::post('/chat-groups/store', [App\Http\Controllers\Admin\ChatGroupController::class, 'storegroupInfo'])->name('admin.chat-groups.store');

});

// User Routes
Route::get('/user/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('user.dashboard');

Route::get('/user/profile', [App\Http\Controllers\DashboardController::class, 'profile'])->name('user.profile');


// Common Routes
Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');

Route::get('messages', [App\Http\Controllers\ChatController::class, 'messages'])
    ->name('messages');

Route::post('messages', [App\Http\Controllers\ChatController::class, 'messageStore'])
    ->name('messageStore');

Route::post('/send-message', [App\Http\Controllers\ChatController::class, 'send_message'])->name('send-message');
Route::get('/redirects', [App\Http\Controllers\HomeController::class, 'index']);

//
