<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = [
            'create_role','edit_role','read_role','delete_role',
            'create_ability','edit_ability','read_ability','delete_ability'
        ];
        foreach ($abilities as $ability) {

            DB::table('abilities')->insert([
                'name' => $ability,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
           
        }
         
        
}
