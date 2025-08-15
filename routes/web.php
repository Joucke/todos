<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduledTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Groups management
    Route::resource('groups', GroupController::class);
    Route::delete('groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::patch('groups/sort', [GroupController::class, 'sort'])->name('groups.sort');

    // Task Lists (nested under groups for creation)
    Route::resource('groups.task-lists', TaskListController::class)
        ->only(['index', 'create', 'store'])
        ->parameter('task-lists', 'taskList');

    Route::resource('task-lists', TaskListController::class)
        ->except(['create', 'store', 'index'])
        ->parameter('task-lists', 'taskList');

    Route::patch('groups/{group}/task-lists/sort', [TaskListController::class, 'sort'])
        ->name('groups.task-lists.sort');

    // Tasks (nested under task lists)
    Route::resource('task-lists.tasks', TaskController::class)
        ->except(['index'])
        ->parameter('task-lists', 'taskList');

    // Scheduled Tasks (task completions)
    Route::resource('tasks.scheduled-tasks', ScheduledTaskController::class)
        ->only(['store', 'destroy'])
        ->parameter('scheduled-tasks', 'scheduledTask');

    // Invitations
    Route::resource('groups.invitations', InvitationController::class)
        ->only(['index', 'store', 'destroy'])
        ->parameter('invitations', 'invitation');

    Route::patch('invitations/{invitation}/accept', [InvitationController::class, 'accept'])
        ->name('invitations.accept');

    Route::patch('invitations/{invitation}/decline', [InvitationController::class, 'decline'])
        ->name('invitations.decline');

    // User's pending invitations
    Route::get('invitations', [InvitationController::class, 'pending'])
        ->name('invitations.pending');
});

require __DIR__.'/auth.php';
