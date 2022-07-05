<?php


namespace App\Models\Interfaces;

use Illuminate\Support\Collection;

/**
 * テーブルからKeyValueListを作成するモデル用のインターフェース
 * Interface KeyValueListModelInterface
 * @package App\Models\Interfaces
 */
interface KeyValueListModelInterface
{
    /**
     * valueの値となる列名
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListValue();

    /**
     * keyの値となる列名
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListKey();

    /**
     * 並べ替えの基準となる列名
     * @return string
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy();

    /**
     * キャッシュキーを返す（アプリケーション内でユニークとなるよう実装すること）
     * @return stirng
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListCacheKey();

    /**
     * KEyValueListを返す
     * @return Collection
     * @author k.yamamoto@balocco.info
     */
    static public function getKeyValueList();
}