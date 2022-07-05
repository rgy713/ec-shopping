<?php


namespace App\Environments\Production\Database\Seeder;

use App\Environments\Producttion\Database\Seeder\InitSystemSettingSeeder;
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
        //システム設定
        $this->call(InitSystemSettingSeeder::class);

    }

}