<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Customer::class, function (Faker $faker) {
    return [
        'name01'=>$faker->lastName,
        'name02'=>$faker->firstName,
        'kana01'=>'ミョウジ',
        'kana02'=>'ナマエ',
        'zipcode'=>$faker->postcode,
        'prefecture_id'=>$faker->numberBetween(1,47),
        'address01'=>$faker->address,
        'address02'=>$faker->address,
//        'requests_for_delivery'=>,
        'email'=>$faker->safeEmail,
        'phone_number01'=>str_replace("-","",$faker->phoneNumber),
        'phone_number02'=>str_replace("-","",$faker->phoneNumber),
        'birthday'=>$faker->date(),
        'password'=>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
//        'first_buy_date'=>,
//        'last_buy_date'=>,
        'buy_times'=>0,
        'buy_total'=>0,
        'buy_volume'=>0,
        'no_phone_call_flag'=>$faker->boolean,
        'mail_magazine_flag'=>$faker->boolean,
        'dm_flag'=>$faker->boolean,
        'wholesale_flag'=>$faker->boolean,
        'pfm_status_id'=>$faker->numberBetween(1,10),
//        'advertising_media_code'=>,
        'registration_route_id'=>$faker->numberBetween(1,2),
        'customer_status_id'=>$faker->numberBetween(2,3),
    ];
},'for_dev');
