<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['ИС-31(д)']
        ];
        $permissions = [
            ['student'],
            ['teacher'],
            ['admin']
        ];
        $users = [
            ['Pinky', 'Pinky', 'Pinky', 'Haired', 'Пинкович', null , 2],
            ['Sergo', 'Sergo', 'Сергей', 'Mikhaylov', 'Ur`evi4', 1, 1],
            ['Vanja', 'Vanja', 'Ваня', 'Кузык', '', 1, 1],
        ];

        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
        DB::table('permissions')->truncate();
        $sql_groups = [];
        $sql_permissions = [];
        $sql_users = [];
        foreach ($groups as $group) {
            $sql_groups[] = [
                'group_name' => $group[0],
            ];
        }
        foreach ($permissions as $permission) {
            $sql_permissions[] = [
                'permission' => $permission[0],
            ];
        }
        foreach ($users as $user) {
            $sql_users[] = [
                'login' => $user[0],
                'password' => Hash::make($user[1]),
                'name' => $user[2],
                'surname' => $user[3],
                'patronymic' => $user[4],
                'group_id' => $user[5],
                'permission_id' => $user[6],
                'avatar' => rand(0, 100) > 50 ? 'images/default-avatar.jpg' : 'images/default-avatar2.jpg',
            ];
        }
        DB::table('groups')->insert($sql_groups);
        DB::table('permissions')->insert($sql_permissions);
        DB::table('users')->insert($sql_users);
        Schema::enableForeignKeyConstraints();
    }
}
