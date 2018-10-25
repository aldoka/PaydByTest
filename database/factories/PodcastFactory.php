<?php

use App\Podcast;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    Podcast::class,
    function (Faker $faker) {
        $company      = $faker->company;
        $name         = mb_substr($company, 0, 123) . mt_rand(0, 10000);
        $marketingUrl = $faker->url . mt_rand(0, 10000);
        $feedUrl      = $faker->url . mt_rand(0, 10000);

        return [
            'name' => $name,
            'description'   => $faker->realText(1000),
            'marketing_url' => $marketingUrl,
            'feed_url' => $feedUrl,
            'image'    => $faker->imageUrl(),
            'status'   => $faker->randomElement(Podcast::getAllStatuses()),
        ];
    }
);

