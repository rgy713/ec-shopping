<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->text('name');
            $table->text('code')->unique();
            $table->text('creator');
            $table->smallInteger('creator_id');
            $table->boolean('user_visible');
            $table->boolean('admin_visible');

            $table->smallInteger('sales_target_id');
            $table->smallInteger('sales_route_id');
            $table->smallInteger('item_lineup_id');
            $table->smallInteger('volume');
            $table->smallInteger('product_delivery_type_id')->nullable();//product_delivery_types:無料プレゼントの場合、NULL保存
            $table->smallInteger("undelivered_summary_classification_id");
            $table->smallInteger('sale_limit_at_once')->nullable();
            $table->smallInteger('sale_limit_for_one_customer')->nullable();
            $table->integer('price');
            $table->boolean('periodic_batch_discount_to_zero_flag')->default(false);
            $table->integer('periodic_first_nebiki')->nullable();
            $table->integer('catalog_price');
            $table->boolean('is_lp')->default(false);
            $table->boolean('is_periodic')->default(false);

            //2019-02-06:追加　商品購入時、支払い方法を固定する。
            $table->smallInteger('fixed_payment_method_id')->nullable();
            //2019-02-06:追加　固定の定期間隔：fixed_periodic_intervalsを参照
            $table->smallInteger('fixed_periodic_interval_id')->nullable();

            $table->timestamps();

            //インデクス
            $table->index('name');
            $table->index('code');

            $table->foreign('sales_target_id')->references('id')->on('sales_targets')->onDelete("RESTRICT");
            $table->foreign('sales_route_id')->references('id')->on('sales_routes')->onDelete("RESTRICT");
            $table->foreign('item_lineup_id')->references('id')->on('item_lineups')->onDelete("RESTRICT");
            $table->foreign('undelivered_summary_classification_id')->references('id')->on('undelivered_summary_classifications')->onDelete("RESTRICT");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
