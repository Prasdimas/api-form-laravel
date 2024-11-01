<?php

namespace Database\Seeders;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            Skill::create(['name' => 'PHP']);
            Skill::create(['name' => 'PostgreSQL']);
            Skill::create(['name' => 'API (JSON,REST)']);
            Skill::create(['name' => 'Version Control System (Gitlab,Github)']);
        
    }
}
