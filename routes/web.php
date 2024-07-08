<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmActivityController;
use App\Admin\Controllers\HomeController; 

use App\Admin\Controllers\FarmAnimalController;
use App\Admin\Controllers\HealthRecordController;
use Illuminate\Support\Facades\DB;
use App\Models\FarmActivity;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and assigned to the "web" middleware group.
| Now create something great!
|
*/

Route::view('auth/register', 'auth.register');

// routes/web.php

Route::get('/calendar', [FarmActivityController::class, 'index'])->name('event.index');
//Route::post('/calendar/events', [FarmActivityController::class, 'store'])->name('event.store');
Route::get('/user-activity', [HomeController::class, 'index'])->name('user-activity');
Route::get('/financial-summary-data', [HomeController::class, 'index'])->name('financial-summary-data');
// routes/web.php

Route::get('farmers-farms/{id}', [FarmAnimalController::class, 'getFarms'])->name('farm.animals');
Route::get('farms-animals/{id}', [HealthRecordController::class, 'getAnimals'])->name('farm.animals');
Route::get('farmer-farms/{id}', [HealthRecordController::class, 'getFarms'])->name('farm.animals');


