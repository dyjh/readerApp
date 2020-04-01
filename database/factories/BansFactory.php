<?php

use Faker\Generator as Faker;

$factory->define(App\Models\baseinfo\Ban::class, function (Faker $faker) {
    $bans = \App\Models\baseinfo\Ban::count();
    $ban = "1班";
    if ($bans > 0) {
        $id = $bans + 1;
        $ban = "{$id}班";
    }

    return [
        'name' => $ban
    ];
});
