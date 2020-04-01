<?php

use Illuminate\Database\Seeder;

class BorrowCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\shares\BorrowComment::class, 50)->create();
    }
}
