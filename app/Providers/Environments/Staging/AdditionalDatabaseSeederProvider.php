<?php

namespace App\Providers\Environments\Staging;

use Illuminate\Support\ServiceProvider;
use App\Environments\Staging\Database\Seeder\AdditionalDatabaseSeeder;

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