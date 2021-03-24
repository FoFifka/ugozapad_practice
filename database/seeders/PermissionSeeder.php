<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['student'],
            ['trainee'],
            ['employer'],
            ['teacher'],
            ['admin']
        ];

        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        $sql = [];
        foreach ($users as $user) {
            $sql[] = [
                'permission' => $user[0],
            ];
        }
        DB::table('permissions')->insert($sql);
        Schema::enableForeignKeyConstraints();
    }
}
