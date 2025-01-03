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
            'create-user','edit-user','read-user','delete-user',
            'create-role','edit-role','read-role','delete-role',
            'create-ability','edit-ability','read-ability','delete-ability'
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
