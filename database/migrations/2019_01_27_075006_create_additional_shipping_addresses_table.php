<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_shipping_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->text('name01');
            $table->text('name02');
            $table->text('kana01');
            $table->text('kana02');
            $table->string('zipcode',7);
            $table->smallInteger('prefecture_id');
            $table->text('address01');
            $table->text('address02');
            $table->text('requests_for_delivery')->nullable();
            $table->text('phone_number01');
            $table->text('phone_number02')->nullable();

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('prefecture_id')->references('id')->on('prefectures');
            $table->index("customer_id");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_shipping_addresses');
    }
}
