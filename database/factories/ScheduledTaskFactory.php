<?php

use App\Task;
use Faker\Generator as Faker;

$factory->define(App\ScheduledTask::class, function (Faker $faker) {
    return [
    	'task_id' => function () {
    		return factory(Task::class)->create()->id;
    	},
    	'scheduled_at' => $faker->dateTimeBetween('now', '+5 days'),
    ];
});
