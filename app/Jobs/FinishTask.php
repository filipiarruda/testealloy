<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Task;

class FinishTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tries = 3;
    protected $task; //Dados da task

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newStatus = !$this->task->finalizado;
        Log::info("Removendo task {$this->task->id}");
        Task::destroy($this->task->id);
        Log::info("Remoção concluída {$this->task->id} com sucesso!");
    }
}
