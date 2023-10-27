<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropViews extends Command
{
    protected $signature = 'db:drop-views';

    protected $description = 'Drop all database views';

    public function handle()
    {
        $databaseName = config('database.connections.mysql.database');
        $views = DB::select("SELECT table_name FROM information_schema.views WHERE table_schema = ?", [$databaseName]);

        foreach ($views as $view) {
            DB::statement("DROP VIEW IF EXISTS {$view->table_name}");
        }

        $this->info('All views dropped successfully.');
    }
}
