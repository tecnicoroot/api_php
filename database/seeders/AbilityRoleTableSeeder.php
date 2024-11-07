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
        $users = User::all()->contains('Admin');
        $roles = Role::all()->where('name', '=', 'Administrador');;
        $abilities = Ability::all();
        //$abilities =[];
        //dd($users,$roles,$abilities);


        foreach ($abilities as $ability) {
            echo $ability->name .PHP_EOL;
            foreach ($roles as $role){
                echo $role->abilities()->attach($ability->id,['created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
            }
        }
        

        
        //DB::table('roles')->insert([
        //    'name' => 'Administrador',
        //    'description' => 'Possui acesso a todas as funções/rotas do sistema',
        //    'created_at' => date("Y-m-d H:i:s"),
        //    'updated_at' => date("Y-m-d H:i:s")
        //]);
        
    }
}
