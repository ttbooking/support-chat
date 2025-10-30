<?php

beforeEach(function () {
    config(['support-chat.user_model' => Illuminate\Foundation\Auth\User::class]);
});

it('returns a successful response', function () {
    $this
        ->actingAs(Orchestra\Testbench\Factories\UserFactory::new()->makeOne())
        // ->withSession(['banned' => false])
        ->get('/support-chat/api/users')
        ->assertStatus(200);
});
