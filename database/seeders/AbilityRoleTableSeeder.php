<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbilityRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $roles = Role::all()->where('name', '=', 'Administrador');
        $abilities = Ability::all();
       
        foreach ($abilities as $ability) {

            DB::table('ability_role')->insert([
                'role_id' => $roles[0]['id'],
                'ability_id' => $ability->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }     
    }
}

