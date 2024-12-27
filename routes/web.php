<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImportController;
use App\Http\Middleware\CheckFieldOffice;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [ImportController::class, 'dashboard'] )->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/peppcodes/generate-pdf', [Importcontroller::class, 'generatePDF']);
Route::post('/importacic/pdf', [Importcontroller::class, 'acicPDF']);
Route::get('/importacic/del', [Importcontroller::class, 'acicdel']);
Route::get('/importacic/txt', [Importcontroller::class, 'acictxt'])->name('acictxt');;

Route::get('/peppcodes/notify', [Importcontroller::class, 'notify'])->middleware(['auth', 'verified'])->name('notify');
Route::get('/peppcodes/export', [Importcontroller::class, 'export'])->middleware(['auth', 'verified'])->name('export');
Route::get('/peppcodes', [Importcontroller::class, 'view'])->middleware(['auth', 'verified'])->name('peppcodes');
Route::get('/peppcodes/filter', [ImportController::class, 'filter'])->middleware(['auth', 'verified']);

//*upload, figuring out how to update with pepp code tagging
Route::get('/peppcodes/update', function () {return view('update');})->middleware([CheckFieldOffice::class])->name('update');

Route::post('/import', [ImportController::class, 'import'])->middleware([CheckFieldOffice::class])->name('import');
Route::post('/importacic', [ImportController::class, 'importacic'])->middleware([CheckFieldOffice::class])->name('importacic');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Project ACIC
Route::get('/acic', [Importcontroller::class, 'acic'])->middleware(['auth', 'verified'])->name('acic');


require __DIR__.'/auth.php';
