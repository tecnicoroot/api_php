<?php

use App\Models\Role;
use App\Models\User;


$app = require __DIR__.'/bootstrap/app.php';


$app->run();

//dd(User::all());
$user = User::all()->where('id', 6);
$roles = Role::all();

//dd(
    //$user[0]->name, 
    //$roles[0]->name, 
//    $user[0]->roles());
foreach ($user[0]->roles as $role) {
    echo $role->name
    . PHP_EOL;
}

foreach ($roles[0]->users as $role) {
    echo $role->name
    . PHP_EOL;
}

foreach ($roles[0]->abilities as $ability) {
    echo $ability->name
    . PHP_EOL;
}

print_r(auth());

?>

