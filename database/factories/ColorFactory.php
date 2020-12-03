<?php

use Faker\Generator as Faker;

$factory->define(App\Color::class, function (Faker $faker) {
    return [
        'color' => $faker->unique()->colorName,
        'hexcolor' => $faker->unique()->hexcolor,
    ];
});
