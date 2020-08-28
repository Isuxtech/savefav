<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Site::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1,5),
        'title' => $faker->text,
        'category' => $faker->domainName,
        'description' => $faker->sentence(20),
        'url' => $faker->url,
    ];
});
