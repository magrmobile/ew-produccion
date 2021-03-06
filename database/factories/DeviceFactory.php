<?php

use Faker\Generator as Faker;

$factory->define(App\Device::class, function (Faker $faker) {
    return [
        'serial_number' => $faker->unique()->swiftBicNumber,
        //'mac_address' => $faker->unique()->macAddress,
        'device_name' => $faker->unique()->userName,
    ];
});