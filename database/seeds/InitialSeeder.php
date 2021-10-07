<?php

namespace database\seeds;
use Illuminate\Database\Seeder;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // $this->call(ModelsTableSeeder::class);
        $this->call(ParcelBdUserSeeder::class);
    }
}
