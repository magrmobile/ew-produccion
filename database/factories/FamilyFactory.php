<?php

use Faker\Generator as Faker;

$factory->define(App\Family::class, function (Faker $faker) {
    return [
        'family_name' => $faker->unique()->userName,
    ];
});
