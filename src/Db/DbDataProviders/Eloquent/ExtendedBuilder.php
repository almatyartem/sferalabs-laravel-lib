<?php

namespace LaravelSferaLibrary\Db\DbDataProviders\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use LaravelSferaLibrary\Contracts\QueryBuilderContract;

class ExtendedBuilder extends Builder implements QueryBuilderContract
{
    /**
     * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Query\Expression|string $column
     * @param string $direction
     * @return ExtendedBuilder
     */
    public function orderBy($column, $direction = 'asc')
    {
        return parent::orderBy($column, $direction);
    }

    /**
     * @param mixed ...$groups
     * @return ExtendedBuilder
     */
    public function groupBy(...$groups)
    {
        return parent::groupBy(...$groups);
    }

    /**
     * @param array|\Closure|\Illuminate\Database\Query\Expression|string $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return ExtendedBuilder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return parent::where($column, $operator, $value, $boolean);
    }

    /**
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @param false $not
     * @return ExtendedBuilder
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        return parent::whereIn($column, $values, $boolean, $not);
    }

    /**
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @return ExtendedBuilder
     */
    public function whereNotIn($column, $values, $boolean = 'and')
    {
        return parent::whereNotIn($column, $values, $boolean);
    }

    /**
     * @param $group
     * @param string $boolean
     * @return ExtendedBuilder
     */
    public function groupWhere($group, $boolean = 'and')
    {
        return parent::where($group, null, null, $boolean);
    }

    /**
     * @param int $value
     * @return ExtendedBuilder
     */
    public function limit($value)
    {
        return parent::limit($value);
    }

    /**
     * @param int $value
     * @return ExtendedBuilder
     */
    public function offset($value)
    {
        return parent::offset($value);
    }

    /**
     * @param array|string $relations
     * @param null $callback
     * @return ExtendedBuilder
     */
    public function with($relations, $callback = null)
    {
        return parent::with($relations, $callback);
    }

    /**
     * @param string[] $columns
     * @return ExtendedBuilder
     */
    public function select($columns = ['*'])
    {
        return parent::select($columns);
    }

    /**
     * @param string[] $columns
     * @return ExtendedBuilder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns);
    }

    /**
     * @param string[] $columns
     * @return ExtendedBuilder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function first($columns = ['*'])
    {
        return parent::first($columns);
    }

    /**
     * @param string $columns
     * @return int
     */
    public function count($columns = '*') : int
    {
        return parent::count($columns);
    }

    /**
     * @param string $relation
     * @param \Closure|null $callback
     * @param string $operator
     * @param int $count
     * @return ExtendedBuilder|Builder
     */
    public function whereHas($relation, \Closure $callback = null, $operator = '>=', $count = 1)
    {
        return parent::whereHas($relation, $callback, $operator, $count);
    }

    /**
     * @param string $relation
     * @param \Closure|null $callback
     * @return ExtendedBuilder|Builder
     */
    public function whereDoesntHave($relation, \Closure $callback = null)
    {
        return parent::whereDoesntHave($relation, $callback);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Relations\Relation|string $relation
     * @param string $operator
     * @param int $count
     * @param string $boolean
     * @param \Closure|null $callback
     * @return ExtendedBuilder|Builder
     */
    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', \Closure $callback = null)
    {
        return parent::has($relation, $operator, $count, $boolean, $callback);
    }
}
