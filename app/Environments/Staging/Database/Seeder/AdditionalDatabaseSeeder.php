<?php


namespace App\Environments\Staging\Database\Seeder;

use Illuminate\Database\Seeder;

class AdditionalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TestAdminSeeder::class);
    }

}