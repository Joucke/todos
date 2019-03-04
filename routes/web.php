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
    Route::get('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('groups', GroupsController::class);
    Route::resource('groups.task_lists', TaskListsController::class, ['only' => ['create', 'store']]);
    Route::resource('task_lists', TaskListsController::class, ['except' => ['create', 'store', 'index']]);
    Route::resource('task_lists.tasks', TasksController::class, ['except' => 'index']);
    Route::resource('tasks.completed_tasks', CompletedTasksController::class);
    Route::resource('groups.users', GroupUsersController::class, ['only' => ['destroy']]);
    Route::resource('groups.invites', GroupInvitationsController::class);
    Route::resource('users', UsersController::class);
    Route::patch('users/{user}/groups', 'UserGroupOrder')->name('groups.sort');
    Route::resource('invites', InvitesController::class, ['only' => ['index', 'destroy']]);
    Route::get('invitations', 'Invitations')->name('invitations');
});
