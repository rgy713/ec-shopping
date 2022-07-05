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

$factory->define(App\Models\AdditionalShippingAddress::class, function (Faker $faker) {
    return [
        'customer_id'=>1,
        'name01' => $faker->lastName,
        'name02' => $faker->firstName,
        'kana01' => 'フリガナ',
        'kana02' => 'フリガナ',
        'zipcode' => $faker->postcode,
        'prefecture_id' => $faker->numberBetween(1,47),
        'address01'=> $faker->address,
        'address02'=> $faker->address,
        'phone_number01'=> str_replace("-","",$faker->phoneNumber),
        'phone_number02'=> str_replace("-","",$faker->phoneNumber),
    ];
},'for_dev');
