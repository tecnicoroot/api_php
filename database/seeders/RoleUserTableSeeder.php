<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $roles = Role::all()->where('name', '=', 'Administrador');
        $users = User::all();

        //dd($roles[0]->id, $users[0]->id);
       
        foreach ($users as $user) {

            DB::table('role_user')->insert([
                'role_id' => $roles[0]->id,
                'user_id' => $users[0]->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }     
    }
}