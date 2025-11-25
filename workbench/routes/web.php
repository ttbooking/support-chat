<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Models\User;

Route::get('/', static fn () => view('lobby', [
    'users' => User::withCount('rooms')->orderByDesc('rooms_count')->limit(10)->get(),
]))->name('login');
