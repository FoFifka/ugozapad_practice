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
            ['Pinky', 'Pinky', 'Haired', 'Пинкович','Pinky', null , 2, null],
            ['mikhaylov.2002@mail.ru', 'Сергей', 'Михайлов', 'Юрьевич','Sergo', 1 , 4, null],
            ['Nikola', 'Николай', 'Иванов', '', 'Nikola', 1 , 1, "Моя область профиссиональных интересов \"Разработка игр\".\nМое место проживания Юго запад, желательна стажировка с дальнейшим трудоустройством.\nПредпочтительно удалённая форма работы."],
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
                'email' => $user[0],
                'name' => $user[1],
                'surname' => $user[2],
                'patronymic' => $user[3],
                'password' => Hash::make($user[4]),
                'group_id' => $user[5],
                'permission_id' => $user[6],
                'about_me' => $user[7],
                'avatar' => rand(0, 100) > 50 ? 'images/default-avatar.jpg' : 'images/default-avatar2.jpg',
            ];
        }
        DB::table('groups')->insert($sql_groups);
        DB::table('users')->insert($sql_users);
        Schema::enableForeignKeyConstraints();
    }
}
