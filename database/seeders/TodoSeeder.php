<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Todo;


class TodoSeeder extends Seeder
{

    public function run(): void
    {
        Todo::factory()->count(50)->create();
    }
}
