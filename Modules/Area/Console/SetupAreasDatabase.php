<?php

namespace Modules\Area\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Area\Entities\Country;
use Modules\Area\Entities\CurrencyCode;
use Modules\Core\Entities\Migration;
use Symfony\Component\Console\Input\InputArgument;
use Setting;

class SetupAreasDatabase extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'setup:areas {timing}';

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
        switch ($this->argument('timing')) {
            case 'before':
                $this->info('remove old areas tables ....');
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                Schema::dropIfExists('countries');
                Schema::dropIfExists('cities');
                Schema::dropIfExists('states');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                $this->info('areas tables removed');

                $this->info('delete areas migrations records ...');
                Migration::where('migration', 'like', '%cities%')->delete();
                Migration::where('migration', 'like', '%countries%')->delete();
                Migration::where('migration', 'like', '%states%')->delete();
                $this->info('areas migrations records deleted');

                Artisan::call('config:cache');
                break;
            case 'after':

                $this->info('staring setup settings ....');

                $country = optional(Country::where('iso2', 'KW')->first())->id;
                $currency = optional(CurrencyCode::where('code', 'KWD')->first())->id;
                Setting::set('default_country', $country);
                Setting::set('supported_countries', [$country]);

                Setting::set('default_currency', $currency);
                Setting::set('supported_currencies', [$currency]);

                Artisan::call('config:cache');
                $this->info('setup settings finished');
                break;
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['timing', InputArgument::REQUIRED, 'the timing is required in : after , before'],
        ];
    }
}
