<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'owner_id' => function() {
        	return factory(User::class)->create()->id;
        },
    ];
});
