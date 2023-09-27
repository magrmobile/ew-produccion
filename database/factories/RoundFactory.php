<?php

use Faker\Generator as Faker;


$factory->define(App\Round::class, function (Faker $faker) {
    $shifts = ['diurno', 'nocturno'];
    $startHour = $faker->numberBetween(0, 23);
    $endHour = ($startHour + 1) % 24;
    $startDate = $faker->dateTimeThisMonth->format('Y-m-d');
    
    return [
        'machine_id' => function () {
            return factory(App\Machine::class)->create()->id;
        },
        'shift' => $shifts[array_rand($shifts)],
        'start_time' => $startDate . " {$startHour}:00:00",
        'end_time' => $startDate . " {$endHour}:00:00",
        'production_result' => $faker->numberBetween(1, 100),
        'production_goal' => $faker->numberBetween(100, 200),
    ];
});

