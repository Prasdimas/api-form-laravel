<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Skill;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Job::create(['name' => 'Frontend Web Developer']);
        Job::create(['name' => 'Fullstack Web Developer']);
        Job::create(['name' => 'Quality Control']);
        Skill::create(['name' => 'PHP']);
        Skill::create(['name' => 'PostgreSQL']);
        Skill::create(['name' => 'API (JSON,REST)']);
        Skill::create(['name' => 'Version Control System (Gitlab,Github)']);
    }
}
