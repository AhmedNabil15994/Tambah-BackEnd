<?php

namespace Modules\Authorization\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RefreshRolsAndPermission extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'refresh:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('db:seed --class=Database\\\Seeders\\\RolesTableSeeder --force');
    }
}
