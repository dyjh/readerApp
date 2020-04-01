<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\shares\BorrowComment::class, function (Faker $faker) {
    $books = [27, 10, 9, 6];
    $book = $faker->randomElement($books);
    /** @var \App\Models\shares\SharedBook $bookModel */
    $bookModel = \App\Models\shares\SharedBook::find($book);

    /** @var \App\Models\baseinfo\Student $student */
    $student = \App\Models\baseinfo\Student::inRandomOrder()->first();

    return [
        'shared_book_id' => $bookModel->getAttribute('id'),
        'shared_book_name' => $bookModel->getAttribute('name'),
        'student_id' => $student->getAttribute('id'),
        'student_name' => $student->getAttribute('name'),
        'student_avatar' => $student->getAttribute('avatar'),
        'content' => implode(' ', $faker->words(20))
    ];
});
