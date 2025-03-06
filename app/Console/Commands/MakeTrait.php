<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Traits/{$name}.php");

        if (File::exists($path)) {
            $this->error('Traits already exists!');
            return;
        }

        File::ensureDirectoryExists(app_path('Traits'));

        $stub = <<<PHP
        <?php

        namespace App\Traits;

        class {$name}
        {
            public function handle()
            {
                // Traits logic here
            }
        }
        PHP;

        File::put($path, $stub);
        $this->info("Trait {$name} created successfully.");
    }
}