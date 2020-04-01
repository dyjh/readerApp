<?php

use Faker\Generator as Faker;
use App\Models\stores\ProductBook;

$factory->define(ProductBook::class, function (Faker $faker) {
    return [
        'author'      => $faker->name,
        'name'        => $faker->colorName,
        'publisher'   => $faker->colorName,
        'isbn'        => $faker->isbn10,
        'cover'       => $faker->imageUrl(240, 240),
        'videos'      => [],
        'info_images' => [$faker->imageUrl(240, 240),$faker->imageUrl(240, 240),$faker->imageUrl(240, 240)],
        'info_text'   => $faker->realText(50),
        'stock'       => $faker->numberBetween(100, 10000),
        'sells_count' => $faker->numberBetween(100, 10000),
        'tag_price'   => $faker->numerify('###.##'),
        'sell_price'  => $faker->numerify('##.##'),
        'discount'    => $faker->numerify('#.##'),
        'rates'    => $faker->numerify('#.##'),
        'comment_counts' => 0,
    ];
});
