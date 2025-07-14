<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskStatus::insert([
            [
                'name'       => 'new',
                'created_at' => new \DateTime('now'),
            ],
            ['name' => 'in progress','created_at' => new \DateTime('now')],
            ['name' => 'testing','created_at' => new \DateTime('now')],
            ['name' => 'completed','created_at' => new \DateTime('now')],
        ]);
    }
}
