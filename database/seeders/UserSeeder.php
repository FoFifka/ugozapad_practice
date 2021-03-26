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
        $users = [
            ['Pinky', 'Pinky', 'Haired', 'Пинкович','Pinky', null , 2],
            ['Sergo', 'Сергей', 'Михайлов', 'Юрьевич','Sergo', 1 , 1],
            ['Nikola', 'Николай', 'Иванов', '', 'Nikola', 1 , 1],
        ];

        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
        $sql_groups = [];
        $sql_users = [];
        foreach ($groups as $group) {
            $sql_groups[] = [
                'group_name' => $group[0],
            ];
        }
        foreach ($users as $user) {
            $sql_users[] = [
                'login' => $user[0],
                'name' => $user[1],
                'surname' => $user[2],
                'patronymic' => $user[3],
                'password' => Hash::make($user[4]),
                'group_id' => $user[5],
                'permission_id' => $user[6],
                'avatar' => rand(0, 100) > 50 ? 'images/default-avatar.jpg' : 'images/default-avatar2.jpg',
            ];
        }
        DB::table('groups')->insert($sql_groups);
        DB::table('users')->insert($sql_users);
        Schema::enableForeignKeyConstraints();
    }
}
