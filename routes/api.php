<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//blog post api
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// register user api
Route::post('/register', [UserController::class, 'registerUser']);

// Add a task
Route::post('/tasks', [TaskController::class, 'store']);
// Mark a task as completed
Route::put('/tasks/{id}', [TaskController::class, 'markAsCompleted']);
// Get all pending tasks
Route::get('/tasks/pending', [TaskController::class, 'getPendingTasks']);