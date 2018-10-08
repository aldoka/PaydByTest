<?php

use App\Podcast;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    Podcast::class,
    function (Faker $faker) {
        $company = $faker->company;
        return [
            'name' => mb_substr($company, 0, 128),
            'description'   => $faker->realText(1000),
            'marketing_url' => $faker->url,
            'feed_url' => $faker->url,
            'image'    => $faker->imageUrl(),
            'status'   => $faker->randomElement(Podcast::getAllStatuses()),
        ];
    }
);

