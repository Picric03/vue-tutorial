<?php

use App\Comment;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => substr($faker->text, 0, 50),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        }
    ];
});
