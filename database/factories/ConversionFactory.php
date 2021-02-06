<?php

use Faker\Generator as Faker;

$factory->define(App\Conversion::class, function (Faker $faker) {
    return [
        'package' => $faker->unique()->name,
        'factor' => $faker->randomFloat(),
        'type' => $faker->randomElement(['R', 'C'])
    ];
});
