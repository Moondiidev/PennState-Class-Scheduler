<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getSemesterNames() as $semester_name) {
            DB::table('semesters')->insert([
                'name' => $semester_name,
            ]);
        }
    }

    private function getSemesterNames()
    {
        return ["Fall", "Spring", "Summer"];
    }
}
