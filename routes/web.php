<?php

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
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'Dashboard')->name('dashboard');

    Route::resource('groups', GroupsController::class);
    Route::resource('task_lists', TaskListsController::class);
    Route::resource('task_lists.tasks', TasksController::class);
    Route::resource('tasks.completed_tasks', CompletedTasksController::class);
    Route::resource('groups.users', GroupUsersController::class);
});
