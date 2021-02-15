<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Chat;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'title' => 'Test',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt("123456"),
        'role' => 0,
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Chat::class, function (Faker $faker) {
    do {
        $from = rand(1, 30);
        $to = rand(1, 30);
        $is_read = rand(0, 1);
    } while ($from === $to);

    return [
        'from' => $from,
        'to' => $to,
        'message' => $faker->sentence,
        'is_read' => $is_read
    ];
});
