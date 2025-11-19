<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Models\User;

Route::get('/', static fn () => view('lobby', [
    'users' => User::all()->random(min(User::count(), 100)),
]))->name('login');
