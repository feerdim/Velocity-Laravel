<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //permission for games
        Permission::create(['name' => 'games.index']);
        Permission::create(['name' => 'games.create']);
        Permission::create(['name' => 'games.edit']);
        Permission::create(['name' => 'games.delete']);

        //permission for roles
        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.delete']);

        //permission for permissions
        Permission::create(['name' => 'permissions.index']);

        //permission for users
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.delete']);

        //permission for activations
        Permission::create(['name' => 'activations.index']);
        Permission::create(['name' => 'activations.create']);
        Permission::create(['name' => 'activations.edit']);
        Permission::create(['name' => 'activations.delete']);

        //permission for awards
        Permission::create(['name' => 'awards.index']);
        Permission::create(['name' => 'awards.create']);
        Permission::create(['name' => 'awards.edit']);
        Permission::create(['name' => 'awards.delete']);

        //permission for schedules
        Permission::create(['name' => 'schedules.index']);
        Permission::create(['name' => 'schedules.create']);
        Permission::create(['name' => 'schedules.edit']);
        Permission::create(['name' => 'schedules.delete']);

        //permission for game_masters
        Permission::create(['name' => 'game_masters.index']);
        Permission::create(['name' => 'game_masters.create']);
        Permission::create(['name' => 'game_masters.edit']);
        Permission::create(['name' => 'game_masters.delete']);


        //permission for crown_packages
        Permission::create(['name' => 'crown_packages.index']);
        Permission::create(['name' => 'crown_packages.create']);
        Permission::create(['name' => 'crown_packages.edit']);
        Permission::create(['name' => 'crown_packages.delete']);

        //permission for mmrs
        Permission::create(['name' => 'mmrs.index']);
        Permission::create(['name' => 'mmrs.create']);
        Permission::create(['name' => 'mmrs.edit']);
        Permission::create(['name' => 'mmrs.delete']);

        //permission for players
        Permission::create(['name' => 'players.index']);
        Permission::create(['name' => 'players.create']);
        Permission::create(['name' => 'players.edit']);
        Permission::create(['name' => 'players.delete']);

        //permission for solos
        Permission::create(['name' => 'solos.index']);
        Permission::create(['name' => 'solos.create']);
        Permission::create(['name' => 'solos.edit']);
        Permission::create(['name' => 'solos.delete']);

        //permission for teams
        Permission::create(['name' => 'teams.index']);
        Permission::create(['name' => 'teams.create']);
        Permission::create(['name' => 'teams.edit']);
        Permission::create(['name' => 'teams.delete']);

        //permission for topup_charges
        Permission::create(['name' => 'topup_charges.index']);
        Permission::create(['name' => 'topup_charges.create']);
        Permission::create(['name' => 'topup_charges.edit']);
        Permission::create(['name' => 'topup_charges.delete']);

        //permission for types
        Permission::create(['name' => 'types.index']);
        Permission::create(['name' => 'types.create']);
        Permission::create(['name' => 'types.edit']);
        Permission::create(['name' => 'types.delete']);
    }
}