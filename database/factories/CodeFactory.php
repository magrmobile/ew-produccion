<?php

use Faker\Generator as Faker;

$factory->define(App\Code::class, function (Faker $faker) {
    return [
        'description' => $faker->unique()->text(25),
    ];
});
