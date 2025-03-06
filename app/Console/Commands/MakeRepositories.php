<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repositories class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Repositories/{$name}.php");

        if (File::exists($path)) {
            $this->error('Repositories already exists!');
            return;
        }

        File::ensureDirectoryExists(app_path('Repositories'));

        $stub = <<<PHP
        <?php

        namespace App\Repositories;

        class {$name}
        {
            public function handle()
            {
                // Repositories logic here
            }
        }
        PHP;

        File::put($path, $stub);
        $this->info("Repositories {$name} created successfully.");
    }
}