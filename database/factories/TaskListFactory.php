<?php

use Faker\Generator as Faker;

$factory->define(App\TaskList::class, function (Faker $faker) {
    return [
        'group_id' => function () {
        	return factory(Group::class)->create()->id;
        },
        'title' => $faker->sentence(3),
    ];
});
