<?php

use App\Comment;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    Comment::class,
    function (Faker $faker) {
        $comment = $faker->realText(1000);
        return [
            'author_name' => $faker->name,
            'author_email' => $faker->email,
            'comment' => $comment,
            'comment_substr' => substr($comment, 0, 191),
        ];
    }
);
