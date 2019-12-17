<?php

use App\Photo;
use App\User;
use Faker\Generator as Faker;

$factory->define(Photo::class, function (Faker $faker) {
    return [
        'id' => str_random(12),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'filename' => str_random(12) . '.jpg',
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
