<?php

use Faker\Generator as Faker;

$factory->define(App\Models\baseinfo\Student::class, function (Faker $faker) {

    $provinces = ['浙江'];
    $cities = ['杭州'];
    $districts = ['淳安县区'];
    $schoolIds = ['2'];
    $schoolNames = ['杭州市西兴实验小学'];

    return [
        'total_beans' => 0,
        'name' => $faker->name,
        'password' => bcrypt('1096'),
        'phone' => $faker->phoneNumber,
        'city' => $faker->randomElement($cities),
        'province' => $faker->randomElement($provinces),
        'district' => $faker->randomElement($districts),
        'school_id' => $faker->randomElement($schoolIds),
        'school_name' => $faker->randomElement($schoolNames),
        'grade_id' => $faker->randomElement(['1']),
        'grade_name' => $faker->randomElement(['1年级']),
        'ban_id' => $faker->randomElement(['1']),
        'ban_name' => $faker->randomElement(['1班']),
        'read_count' => $faker->randomDigit,
        'share_count' => $faker->randomDigit
    ];
});
