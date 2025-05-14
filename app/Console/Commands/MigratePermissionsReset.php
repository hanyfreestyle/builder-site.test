<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePermissionsReset extends Command {
    protected $signature = 'permissions:reset ';
    protected $description = 'permissions:reset';

    public function handle() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info("🎉 Done!");
    }
}
