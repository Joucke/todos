<?php

use App\TaskList;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'task_list_id' => function () {
        	return factory(TaskList::class)->create()->id;
        },
        'title' => $faker->sentence(3),
        'interval' => $faker->numberBetween(1, 65),
    ];
});
