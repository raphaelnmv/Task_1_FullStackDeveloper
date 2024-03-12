<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', [ClientController::class, 'index']);
Route::post('/store', [ClientController::class, 'store'])->name('store');
Route::get('/fetchall', [ClientController::class, 'fetchAll'])->name('fetchAll');
Route::delete('/delete', [ClientController::class, 'delete'])->name('delete');
Route::get('/edit', [ClientController::class, 'edit'])->name('edit');
Route::post('/update', [ClientController::class, 'update'])->name('update');
