<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use mikehaertl\shellcommand\Command as Exec;
use App\Release as Rel;

class release extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'release:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Release';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $command = new Exec('git pull');
        if ($command->execute()) {
            $content = $command->getOutput();
        } else {
            $content = $command->getError();
        }
        
        $affectedRows = Rel::where('released', false)->update(
            [
                'execution_message' => $content,
                'released' => true
            ]
        );
        
        dd($affectedRows);
    }
}
