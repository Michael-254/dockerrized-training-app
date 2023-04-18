<?php

use App\Http\Controllers\TrainingRequestController;
use App\Http\Controllers\PDFController;
use App\Models\TrainingRequest;
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
    return redirect('admin/login');
});

Route::get('/test', function () {
    return TrainingRequest::find(6)->trainings;
});

Route::get(
    '/submit/training-request/{record}/for-review/',
    [TrainingRequestController::class, 'submitForReview']
)
    ->name('training.request.submission')
    ->middleware('auth');

Route::get('write-data', [PDFController::class,'index']);
Route::view('/import','users-upload');
Route::post('/import',[PDFController::class,'import'])->name('import');