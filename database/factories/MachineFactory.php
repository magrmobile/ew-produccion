<?php

use Faker\Generator as Faker;

$factory->define(App\Machine::class, function (Faker $faker) {
    return [
        'machine_name' => $faker->unique()->name,
        'machine_code' => $faker->unique()->swiftBicNumber,
        'warehouse' => $faker->randomElement(['AL', 'CU']),
    ];
});

$factory->state(App\Machine::class, 'aluminio', [
    'warehouse' => 'AL'
]);

$factory->state(App\Machine::class, 'cobre', [
    'warehouse' => 'CU'
]);
