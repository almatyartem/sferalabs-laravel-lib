<?php

namespace LaravelSferaLibrary\Db;

use LaravelSferaLibrary\Contracts\QueryBuilderContract;
use LaravelSferaLibrary\Contracts\BaseSearchContract;
use LaravelSferaLibrary\Contracts\DbDataProviderContract;

abstract class BaseSearch implements BaseSearchContract
{
    /**
     * @var QueryBuilderContract
     */
    protected $builder;

    /**
     * @var DbDataProviderContract
     */
    protected $dbDataProvider;

    /**
     * BaseSearchContext constructor.
     * @param DbDataProviderContract $dbDataProvider
     */
    function __construct(DbDataProviderContract $dbDataProvider)
    {
        $this->dbDataProvider = $dbDataProvider;
        $this->refresh();
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->builder = $this->dbDataProvider->getBuilder();

        return $this;
    }
/**
     * @return BaseEntity|null
     */
    public function first()
    {
        $result = $this->dbDataProvider->first($this->builder);

        $this->refresh();

        return $result;
    }

    /**
     * @return BaseEntity[]|null
     */
    public function find() : ?array
    {
        $result =  $this->dbDataProvider->get($this->builder);

        $this->refresh();

        return $result;
    }

    /**
     * @param string[] $columns
     * @return array
     */
    public function firstAsArray($columns = ['*']) : array
    {
        return $this->dbDataProvider->firstAsArray($this->builder, $columns);
    }

    /**
     * @param string[] $columns
     * @return array
     */
    public function findAsArray($columns = ['*']) : array
    {
        return $this->dbDataProvider->getAsArray($this->builder, $columns);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->dbDataProvider->count($this->builder);
    }

    /**
     * @param $groups
     * @return $this|BaseSearchContract
     */
    public function groupBy($groups) : BaseSearchContract
    {
        $this->builder = $this->builder->groupBy($groups);

        return $this;
    }

    /**
     * @param $columns
     * @param string $direction
     * @return $this|BaseSearchContract
     */
    public function orderBy($columns, $direction = 'asc') : BaseSearchContract
    {
        $this->builder = $this->builder->orderBy($columns, $direction);

        return $this;
    }

    /**
     * @param $group
     * @param string $boolean
     * @return $this|BaseSearchContract
     */
    public function where($group, $boolean = 'and') : BaseSearchContract
    {
        $this->builder = $this->builder->groupWhere($group, $boolean);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereEqual(string $field, $value) : BaseSearchContract
    {
        $this->builder->where($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereIn(string $field, array $value) : BaseSearchContract
    {
        $this->builder->whereIn($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereNotIn(string $field, array $value) : BaseSearchContract
    {
        $this->builder->whereNotIn($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereNotEqual(string $field, $value) : BaseSearchContract
    {
        $this->builder->where($field, '!=', $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereLike(string $field, $value) : BaseSearchContract
    {
        $this->builder->where($field, 'like', $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearchContract
     */
    public function whereMoreThan(string $field, $value, bool $orEqual = false) : BaseSearchContract
    {
        $this->builder->where($field, '>'.($orEqual ? '=' : ''), $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @param bool $orEqual
     * @return $this|BaseSearchContract
     */
    public function whereLessThan(string $field, $value, bool $orEqual = false) : BaseSearchContract
    {
        $this->builder->where($field, '<'.($orEqual ? '=' : ''), $value);

        return $this;
    }

    /**
     * @param int $value
     * @return $this|BaseSearchContract
     */
    public function limit(int $value) : BaseSearchContract
    {
        $this->builder->limit($value);

        return $this;
    }

    /**
     * @param int $value
     * @return BaseSearchContract
     */
    public function offset(int $value) : BaseSearchContract
    {
        $this->builder->offset($value);

        return $this;
    }

    /**
     * @param $relations
     * @return $this|BaseSearchContract
     */
    public function with($relations) : BaseSearchContract
    {
        $this->builder->with($relations);

        return $this;
    }

    /**
     * @param $relation
     * @param \Closure|null $callback
     * @param string $operator
     * @param int $count
     * @return $this|BaseSearchContract
     */
    public function whereHas($relation, \Closure $callback = null, $operator = '>=', $count = 1) : BaseSearchContract
    {
        $this->builder->whereHas($relation, $callback, $operator, $count);

        return $this;
    }

    /**
     * @param $relation
     * @param \Closure|null $callback
     * @return $this
     */
    public function whereDoesntHave($relation, \Closure $callback = null)
    {
        $this->builder->whereDoesntHave($relation, $callback);

        return $this;
    }


    /**
     * @param $relation
     * @param string $operator
     * @param int $count
     * @param string $boolean
     * @param \Closure|null $callback
     * @return $this|BaseSearchContract
     */
    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', \Closure $callback = null) : BaseSearchContract
    {
        $this->builder->has($relation, $operator, $count, $boolean, $callback);

        return $this;
    }
}
