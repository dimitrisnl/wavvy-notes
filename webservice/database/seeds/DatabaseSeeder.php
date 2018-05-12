<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Database tables.
     *
     * @var array
     */
    protected $tables = [
        'users',
        'categories',
        'notes',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    }

    /**
     * Clear all tables in the database.
     *
     * @return void
     */
    protected function truncate()
    {

    }
}
