<?php

use Faker\Generator as Faker;

$factory->define(App\Models\baseinfo\Grade::class, function (Faker $faker) {
    $grades = \App\Models\baseinfo\Grade::count();
    $grade = '1年级';
    if ($grades > 0) {
        $id = $grades + 1;
        $grade = "{$id}年级";
    }

    return [
        'name' => $grade
    ];
});
