<?php

use App\Http\Livewire\AreasComponent;
use App\Http\Livewire\ContestFileComponent;
use App\Http\Livewire\ContestsComponent;
use App\Http\Livewire\EventsComponent;
use App\Http\Livewire\PushNotifications;
use App\Http\Livewire\ServicesComponent;
use App\Http\Livewire\SliderComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});


Auth::routes();

Route::get('register', function () {
    abort(404);
});

Route::get('/push', PushNotifications::class);

Route::get('/slider', SliderComponent::class);

Route::get('/events', EventsComponent::class);

Route::get('/areas', AreasComponent::class);

Route::get('/contests/{id}', ContestsComponent::class);

Route::get('/contests/files/{id}', ContestFileComponent::class);

Route::get('/services/{id}', ServicesComponent::class);
