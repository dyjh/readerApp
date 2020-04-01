<?php

use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\stores\ProductBook::class, 100)->create();
//        factory(\App\Models\stores\ProductBookComment::class, 400)->create();
//        factory(\App\Models\stores\Order::class, 100)->create();
    }
}
