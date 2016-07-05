<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Schema;

class MigrateClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all the tables then, migrate and seed.';

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
        $tables = [];

        DB::statement( 'SET FOREIGN_KEY_CHECKS=0' );

        foreach (DB::select('SHOW TABLES') as $k => $v) {
            $tables[] = array_values((array)$v)[0];
        }

        foreach($tables as $table) {
            Schema::drop($table);
            echo "Table ".$table." has been dropped.".PHP_EOL;
        }

        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
    }
}
