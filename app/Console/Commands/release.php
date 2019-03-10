<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use mikehaertl\shellcommand\Command as Exec;
use JakubOnderka\PhpConsoleColor\ConsoleColor;
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
        $releaseDate = date("dmYHis");
        
        $command = new Exec("cp -r ../laravel_deploy/ ../laravel_deploy_{$releaseDate}/");
        
        echo "=========== CREATING THE BACKUP RELEASE \n";
        if ($command->execute()) {
            echo "=========== RELEASE CREATED \n";
            echo $command->getOutput();
            echo "\n";
        } else {
            $content = $command->getError();
        }

        echo "=========== GIT PULL \n";
        $command = new Exec('git pull');
        if ($command->execute()) {
            echo "=========== GIT PULL (DONE) \n";
            echo $command->getOutput();
            echo "\n";
        } else {
            $content = $command->getError();
        }
        
        echo "=========== NPM INSTALL \n";
        $command = new Exec("npm install");
        if ($command->execute()) {
            echo "=========== NPM INSTALL (DONE) \n";
            echo $command->getOutput();
            echo "\n";
        } else {
            $content = $command->getError();
        }

        echo "=========== COMPOSER INSTALL \n";
        $command = new Exec("composer install");
        if ($command->execute()) {
            echo "=========== COMPOSER INSTALL (DONE) \n";
            echo $command->getOutput();
            echo "\n";
        } else {
            $content = $command->getError();
        }

        echo "=========== NPM RUN PRODUCTION \n";
        $command = new Exec("npm run production");
        if ($command->execute()) {
            echo "=========== NPM RUN PRODUCTION (DONE) \n";
            $content = $command->getOutput();
            echo $content;
            echo "\n";
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
