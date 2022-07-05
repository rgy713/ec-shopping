<?php


namespace App\Models\Traits;


use Illuminate\Support\Facades\Cache;

/**
 * Model経由でKeyValueListを取得する場合の処理。
 * name、id、rank
 * の3列が存在するテーブルのモデルに対して適用可能。
 *
 * Trait DefaultKeyValueListTrait
 * @package App\Models\Traits
 */
trait DefaultKeyValueListTrait
{
    /**
     * KeyValueList のValue値にあたる列名を文字列で返す
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListValue(){
        return 'name';
    }

    /**
     * KeyValueList のKey値にあたる列名を文字列で返す
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListKey(){
        return 'id';
    }

    /**
     * KeyValueList の並べ替え基準となる列名を文字列で返す
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy(){
        return 'rank';
    }

    /**
     * キャッシュキーを返す
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListCacheKey(){
        return self::class.'KeyValueList';
    }

    /**
     * KeyValueListをCollection形式で返す
     * モデルごとに実装を変更する場合、このメソッドをオーバーライドする。
     * @return \Illuminate\Support\Collection
     * @author k.yamamoto@balocco.info
     */
    static public function getKeyValueList(){
        return Cache::rememberForever(self::getColumnNameOfListCacheKey(),function () {
            $orderBy=self::getColumnNameOfListOrderBy();
            $label=self::getColumnNameOfListValue();
            $key=self::getColumnNameOfListKey();
            return self::orderBy($orderBy)->get()->pluck($label,$key);
        });

    }

}