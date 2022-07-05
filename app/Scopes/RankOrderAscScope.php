<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * rank 列の昇順でソート
 * Class RankOrderScope
 * @package App\Scopes
 * @author k.yamamoto@balocco.info
 */
class RankOrderAscScope implements Scope
{

    /**
     * Eloquentクエリビルダへ適用するスコープ
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('rank', 'ASC');
    }
}