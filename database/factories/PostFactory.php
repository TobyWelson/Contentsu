<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'id' => Str::random(12),
        'user_id' => fn() => factory(App\User::class)->create()->id,
        'title' => Str::random(5),
        'category' => Str::random(6),
        'view_count' => 0,
        'url' => 'https://www.youtube.com/watch?v=V3-zptKJ4-M',
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});