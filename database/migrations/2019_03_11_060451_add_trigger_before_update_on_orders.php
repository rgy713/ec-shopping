<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerBeforeUpdateOnOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config("database.default")==="pgsql"){
            /**
             * ordersテーブル更新時に起動するトリガの定義：order_count_of_customerの更新禁止
             * ※パフォーマンス上問題になるようであれば、削除も検討する。
             */
            $createFunction="
                CREATE OR REPLACE FUNCTION trigger_orders_before_update()
                RETURNS TRIGGER
                AS '
                  BEGIN
                    IF (OLD.order_count_of_customer IS NOT NULL) THEN 
                      IF (OLD.order_count_of_customer <> NEW.order_count_of_customer) THEN
                          RAISE EXCEPTION ''Updating order_count_of_customer is prohibited.'';
                      END IF;
                    END IF;
                
                    RETURN NEW;
                    END;
                '
                LANGUAGE 'plpgsql'
            ";
            DB::statement($createFunction);


            $createTrigger="
                CREATE TRIGGER 
                 trigger_orders_before_update
                BEFORE UPDATE ON 
                 orders
                FOR EACH ROW
                EXECUTE PROCEDURE 
                 trigger_orders_before_update();
            ";
            DB::statement($createTrigger);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(config("database.default")==="pgsql") {
            DB::statement("DROP TRIGGER trigger_orders_before_update ON orders");
            DB::statement("DROP FUNCTION trigger_orders_before_update()");
        }
    }
}
