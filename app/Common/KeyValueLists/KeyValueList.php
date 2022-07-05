<?php

namespace App\Common\KeyValueLists;

use Illuminate\Support\Collection;

/**
 * Class KeyValueList キーと値のセットの形式の一次元配列を持つクラス。
 * どのようなリスト内容かは、各実装クラスの definition() メソッドに定義する。
 * また、KeyValueListを継承したクラスは、配列として扱うことができる。
 * @package App\Services\Jahis\Prescription\KeyValueList
 */
abstract class KeyValueList extends Collection implements KeyValueListInterface
{

    /**
     * KeyValueList constructor.
     */
    public function __construct($items = [])
    {
        if (count($items) > 0) {
            parent::__construct($items);
        } else {
            parent::__construct($this->definition());
        }
    }

    /**
     * @return array
     */
    abstract public function definition(): array;

}