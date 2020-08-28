<?php

use Illuminate\Database\Seeder;

class siteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Site::class, 50)->create();
    }
}
