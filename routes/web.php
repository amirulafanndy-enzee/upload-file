<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('/files', FileController::class);
Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
Route::put('/files/{id}/rename', [FileController::class, 'rename'])->name('files.rename');
