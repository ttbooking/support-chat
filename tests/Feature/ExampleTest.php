<?php

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;

beforeEach(function () {
    config(['support-chat.user_model' => User::class]);
});

it('returns a successful response', function () {
    $this
        ->actingAs(UserFactory::new()->makeOne())
        // ->withSession(['banned' => false])
        ->get('/support-chat/api/users')
        ->assertStatus(200);
});
