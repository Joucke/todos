<?php

use Faker\Generator as Faker;

$factory->define(App\Invitation::class, function (Faker $faker) {
    return [
        'group_id' => factory(App\Group::class),
        'email' => $faker->email,
    ];
});
