<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([RoleTableSeeder::class]);
        $this->call([AbilityTableSeeder::class]);
        $this->call([AbilityRoleTableSeeder::class]);
        $this->call([UserTableSeeder::class]);
        $this->call([RoleUserTableSeeder::class]);
    }
}
