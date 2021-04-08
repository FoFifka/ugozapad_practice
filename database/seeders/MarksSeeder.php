<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marks = [
            ['Срендяя'],
            ['Выше среднего'],
            ['Хорошист'],
            ['Отличник'],
        ];

        Schema::disableForeignKeyConstraints();
        DB::table('marks')->truncate();
        $sql_marks = [];
        foreach ($marks as $mark) {
            $sql_marks[] = [
                'mark' => $mark[0],
            ];
        }
        DB::table('marks')->insert($sql_marks);
        Schema::enableForeignKeyConstraints();
    }
}
