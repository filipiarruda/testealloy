<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [];
        for ($i = 1; $i <= 15; $i++) {
            $tasks[] = [
                'nome' => 'Tarefa ' . $i,
                'descricao' => 'Descrição da tarefa ' . $i,
                'finalizado' => (bool)random_int(0, 1),
                'data_limite' => rand(0, 1) ? Carbon::now()->addDays(rand(1, 30)) : null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ];
        }

        DB::table('tasks')->insert($tasks);
    }
}
