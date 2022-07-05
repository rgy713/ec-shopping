<?php

namespace App\Providers\Environments\Develop;

use Illuminate\Support\ServiceProvider;
use App\Environments\Develop\Database\Seeder\AdditionalDatabaseSeeder;

class AdditionalDatabaseSeederProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            "AdditionalDatabaseSeeder",
            AdditionalDatabaseSeeder::class
        );
    }

}