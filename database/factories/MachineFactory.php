<?php

use Faker\Generator as Faker;

$factory->define(App\Machine::class, function (Faker $faker) {
    return [
        'machine_name' => $faker->unique()->name,
        'process' => $faker->randomElement(['Trifilado', 'Cableado', 'Fraccionado']),
        'warehouse' => $faker->randomElement(['AL', 'CU']),
    ];
});

$factory->state(App\Machine::class, 'aluminio', [
    'warehouse' => 'AL'
]);

$factory->state(App\Machine::class, 'cobre', [
    'warehouse' => 'CU'
]);
