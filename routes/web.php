<?php

use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\TaskPageController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthPageController::class, 'login'])->name('login');

// Task routes (protected by auth middleware)
Route::redirect('/', '/tasks');
Route::get('/dashboard', [TaskPageController::class, 'dashboard'])->name('tasks.dashboard');
Route::get('/tasks', [TaskPageController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskPageController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{id}', [TaskPageController::class, 'show'])->name('tasks.show');
Route::get('/tasks/{id}/edit', [TaskPageController::class, 'edit'])->name('tasks.edit');
