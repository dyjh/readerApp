<?php

use Faker\Generator as Faker;

$factory->define(App\Models\mooc\Lesson::class, function (Faker $faker) {

    $categories = [1, 2, 3];
    $types = [1, 2, 3];
    $teachers = [1, 2];
    $tags = array_keys(\App\Common\Definition::LESSON_TAG_EXPLAINS);
    $grades = [6, 7, 8, 9];
    $semesters = [1, 2];
    return [
        'teacher_id' => $faker->randomElement($teachers),
        'lesson_category_id' => $faker->randomElement($categories),
        'lesson_type_id' => $faker->randomElement($types),
        'semester_id' => $faker->randomElement($semesters),
        'tag' => $faker->randomElement($tags),
        'grade_id' => $faker->randomElement($grades),
        'name' => $faker->word,
        'desc' => $faker->text(30),
        'price' => $faker->numberBetween(100, 300),
        'sign_dead_line' => $faker->dateTime,
        'sign_count' => $faker->randomDigit,
        'view_count' => $faker->randomDigit,
        'lesson_hour_count' => $faker->randomDigit,
        'is_streamed' => $faker->boolean,
        'broadcast_day_begin' => $faker->dateTime,
        'broadcast_day_end' => $faker->dateTime,
        'broadcast_start_at' => $faker->dateTime,
        'broadcast_ent_at' => $faker->dateTime,
        'cover' => $faker->imageUrl(),
        'images' => [$faker->imageUrl(), $faker->imageUrl()],
        'rates' => $faker->randomDigit
    ];
});
