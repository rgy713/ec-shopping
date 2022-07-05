<?php


namespace App\Common;

use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use \Exception;

/**
 * Class BaseType
 * @package App\Common
 * @author k.yamamoto@balocco.info
 */
abstract class BaseType implements ArrayAccess, Arrayable, IteratorAggregate, Jsonable, JsonSerializable
{

    /**
     * 配列としてアクセスできるメンバ変数の名前を定義。サブクラスで実装。
     * @return array
     */
    abstract public function getAttributes(): array;



    //ArrayAccessインターフェースの実装
    //配列形式の記法 ($baseType['id'] などでの参照等) をサポートする
    /**
     * @param mixed $offset
     * @return bool
     */
    final public function offsetExists($offset)
    {
        $attributes = $this->getAttributes();
        return in_array($offset, $attributes);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->$offset;
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    final public function offsetSet($offset, $value)
    {
        //protected であることを担保するため、外からはsetできない
        return;
    }

    /**
     * @param mixed $offset
     */
    final public function offsetUnset($offset)
    {
        //protected であることを担保するため、外からはunsetできない
        return;
    }

    //Arrayableインターフェースの実装
    //配列への変換方法を定義する。実際に何を返すかは、サブクラスの getAttributes() で実装。
    /**
     * @return array
     */
    final public function toArray()
    {
        $tmpArray = [];
        $attributes = $this->getAttributes();
        foreach ($attributes as $itemKey) {
            $tmpArray[$itemKey] = $this->$itemKey;
        }
        return $tmpArray;
    }

    //IteratorAggregateインターフェースの実装
    //foreach文でのアクセスをサポートする。
    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }


    //Jsonableインターフェースの実装
    //json形式への変換をサポートする
    /**
     * @param int $options
     * @return string
     * @throws Exception
     */
    final public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception(json_last_error_msg());
        }

        return $json;
    }

    //jsonSerializeインターフェースの実装
    //json形式への変換時の内容を定義する
    /**
     * @return array|mixed
     */
    final public function jsonSerialize()
    {
        return $this->toArray();
    }
}
