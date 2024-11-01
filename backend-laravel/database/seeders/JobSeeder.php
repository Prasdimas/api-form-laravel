<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Job::create(['name' => 'Frontend Web Developer']);
        Job::create(['name' => 'Fullstack Web Developer']);
        Job::create(['name' => 'Quality Control']);
    }
}
