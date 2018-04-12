<?php

use Illuminate\Database\Seeder;

class CnareaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $sqlFile = config('database.cnarea_sql_file');
        $command = sprintf("mysql -u %s -p %s %s < %s", $username, $password, $database, $sqlFile);

        passthru($command);

    }
}
