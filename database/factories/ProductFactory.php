<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'product_name' => $faker->unique()->name,
        'metal_type' => $faker->randomElement(['AL', 'CU']),
        'stock' => $faker->randomElement(['PP', 'PT'])
    ];
});

$factory->state(App\Product::class, 'aluminio', [
    'metal_type' => 'AL'
]);

$factory->state(App\Product::class, 'cobre', [
    'metal_type' => 'CU'
]);