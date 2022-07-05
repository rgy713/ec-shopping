<?php

namespace App\Common;

use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class CustomBlade
 * blade拡張をまとめて定義するクラス。
 * このクラスで定義した関数を実際に割り当てる処理はBladeServiceProviderで行う
 * @package App\Utils
 * @author k.yamamoto@balocco.info
 */
class CustomBlade extends BladeCompiler
{

    /**
     * CustomBlade constructor.
     * オーバーライドし、引数無しでnewできるよう実装
     * Todo:この実装が適切なのか？
     */
    public function __construct()
    {
        $app = app();
        parent::__construct($app['files'], $app['config']['view.compiled']);
    }

    /**
     * エラー内容を検証し、エラーがある場合is-invalid を出力する
     * @param $expression $errors
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function isInvalid($expression)
    {
        $args = explode(",", $expression);
        return "<?php if(({$args[0]})->has({$args[1]})){echo 'is-invalid';}; ?>";
    }

    public function datetime($expression)
    {
        $args = explode(",", $expression);
        if (count($args) === 1) {
            return "
            <?php
                echo '<span class=\"local-datetime\">';
                echo {$args[0]}->format(config('fleuri.system.format.datetime.view_default'));
                echo '</span>';  
            ?>";
        }
        if (count($args) === 2) {
            return "<?php
                echo '<span class=\"local-datetime\">'; 
                echo {$args[0]}->format({$args[1]});
                echo '</span>'; 
            ?>";
        }


    }

    public function rateToPercent($expression)
    {
        $args = explode(",", $expression);
        return "<?php echo doubleval({$args[0]}) * 100; ?>";
    }
}