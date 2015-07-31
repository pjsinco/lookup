<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('SpecialtyTableSeeder');
        $this->call('LocationTableSeeder');
        $this->call('PhysicianTableSeeder');

        // $this->call(UserTableSeeder::class);

        Model::reguard();
    }


}
