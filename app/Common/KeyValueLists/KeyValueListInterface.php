<?php
/**
 * Created by PhpStorm.
 * User: yamamoto
 * Date: 2018/08/20
 * Time: 21:56
 */

namespace App\Common\KeyValueLists;

interface KeyValueListInterface
{

    /**
     * key-valueのセットを配列で返す
     * @return array
     */
    public function definition(): array;
}