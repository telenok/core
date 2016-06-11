<?php namespace Telenok\Core\Abstraction\Eloquent\Cache;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\ScopeInterface;

/**
 * @class Telenok.Core.Abstraction.Eloquent.Cache.QueryCacheScope
 * Add scope to global space.
 * 
 * @uses Illuminate.Database.Eloquent.Builder
 * @uses Illuminate.Database.Eloquent.Model
 * @uses Illuminate.Database.Eloquent.ScopeInterface
 * @extends Illuminate.Database.Eloquent.ScopeInterface
 */
class QueryCacheScope implements ScopeInterface {

    /**
     * @method apply
     * Apply the scope to a given Eloquent query builder.
     *
     * @param {Illuminate.Database.Eloquent.Builder} $builder
     * Query builder.
     * @param {Illuminate.Database.Eloquent.Model} $model
     * Eloquent model.
     * @return {void}
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->remember($model->getCacheMinutes())
                ->cacheTags($model->getCacheTags());
    }
}