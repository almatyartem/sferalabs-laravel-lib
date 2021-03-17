<?php

namespace LaravelSferaLibrary\DataLayer;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use LaravelSferaLibrary\DataLayer\DbDataProviders\Eloquent\BaseEloquentModel;
use LaravelSferaLibrary\DataLayer\DbDataProviders\Eloquent\ModelToDTOConverter;
use LaravelSferaLibrary\Exceptions\DbDataProviderException;

abstract class BaseSearch
{
    /**
     * Eloquent can return both builders in different methods. It sucks.
     * @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected $eloquentBuilder;

    /**
     * @var string
     */
    protected string $entityClassName;

    /**
     * @var ModelToDTOConverter
     */
    protected ModelToDTOConverter $converter;

    /**
     * BaseRepository constructor.
     * @param string $entityClassName
     * @param ModelToDTOConverter $converter
     */
    public function __construct(string $entityClassName, ModelToDTOConverter $converter)
    {
        $this->entityClassName = $entityClassName;
        $this->converter = $converter;

        $this->refresh();
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->eloquentBuilder = (new BaseEloquentModel([], $this->entityClassName))->newQuery();

        return $this;
    }

    /**
     * @return BaseEntity|null
     * @throws DbDataProviderException
     */
    public function first()
    {
        try {
            $result = $this->eloquentBuilder->first();
        } catch (RelationNotFoundException $exception) {
            $this->refresh();
            throw new DbDataProviderException('Call to undefined relationship '.$exception->relation.' on entity '.$this->entityClassName);
        }

        $this->refresh();

        return $this->toDto($result);
    }

    /**
     * @return array|null
     * @throws DbDataProviderException
     */
    public function find(): ?array
    {
        try {
            $result = $this->eloquentBuilder->get();
        } catch (RelationNotFoundException $exception) {
            $this->refresh();
            throw new DbDataProviderException('Call to undefined relationship '.$exception->relation.' on entity '.$this->entityClassName);
        }

        $this->refresh();

        return $this->toDto($result);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $result = $this->eloquentBuilder->count();

        $this->refresh();

        return $result;
    }

    /**
     * @param string[] $columns
     * @return array
     */
    public function firstAsArray($columns = ['*']): array
    {
        $result = $this->eloquentBuilder->first($columns);

        return $result ? $result->toArray() : [];
    }

    /**
     * @param string[] $columns
     * @return array
     */
    public function findAsArray($columns = ['*']): array
    {
        $result = $this->eloquentBuilder->get($columns);

        return $result ? $result->toArray() : [];
    }

    /**
     * @param $groups
     * @return $this|BaseSearch
     */
    public function groupBy($groups): BaseSearch
    {
        $this->eloquentBuilder->groupBy($groups);

        return $this;
    }

    /**
     * @param $columns
     * @param string $direction
     * @return $this|BaseSearch
     */
    public function orderBy($columns, $direction = 'asc'): BaseSearch
    {
        $this->eloquentBuilder->orderBy($columns, $direction);

        return $this;
    }

    /**
     * @param $group
     * @param string $boolean
     * @return $this|BaseSearch
     */
    public function where($group, $boolean = 'and'): BaseSearch
    {
        $this->eloquentBuilder->where($group, null, null, $boolean);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearch
     */
    public function whereEqual(string $field, $value): BaseSearch
    {
        $this->eloquentBuilder->where($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearch
     */
    public function whereIn(string $field, array $value): BaseSearch
    {
        $this->eloquentBuilder->whereIn($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearch
     */
    public function whereNotIn(string $field, array $value): BaseSearch
    {
        $this->eloquentBuilder->whereNotIn($field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearch
     */
    public function whereNotEqual(string $field, $value): BaseSearch
    {
        $this->eloquentBuilder->where($field, '!=', $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this|BaseSearch
     */
    public function whereLike(string $field, $value): BaseSearch
    {
        $this->eloquentBuilder->where($field, 'like', $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @param bool $orEqual
     * @return $this|BaseSearch
     */
    public function whereMoreThan(string $field, $value, bool $orEqual = false): BaseSearch
    {
        $this->eloquentBuilder->where($field, '>' . ($orEqual ? '=' : ''), $value);

        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @param bool $orEqual
     * @return $this|BaseSearch
     */
    public function whereLessThan(string $field, $value, bool $orEqual = false): BaseSearch
    {
        $this->eloquentBuilder->where($field, '<' . ($orEqual ? '=' : ''), $value);

        return $this;
    }

    /**
     * @param int $value
     * @return $this|BaseSearch
     */
    public function limit(int $value): BaseSearch
    {
        $this->eloquentBuilder->limit($value);

        return $this;
    }

    /**
     * @param int $value
     * @return BaseSearch
     */
    public function offset(int $value): BaseSearch
    {
        $this->eloquentBuilder->offset($value);

        return $this;
    }

    /**
     * @param $relations
     * @return $this|BaseSearch
     */
    public function with($relations): BaseSearch
    {
        $this->eloquentBuilder->with($relations);

        return $this;
    }

    /**
     * @param $relation
     * @param \Closure|null $callback
     * @param string $operator
     * @param int $count
     * @return $this|BaseSearch
     */
    public function whereHas($relation, \Closure $callback = null, $operator = '>=', $count = 1): BaseSearch
    {
        $this->eloquentBuilder->whereHas($relation, $callback, $operator, $count);

        return $this;
    }

    /**
     * @param $relation
     * @param \Closure|null $callback
     * @return $this
     */
    public function whereDoesntHave($relation, \Closure $callback = null)
    {
        $this->eloquentBuilder->whereDoesntHave($relation, $callback);

        return $this;
    }


    /**
     * @param $relation
     * @param string $operator
     * @param int $count
     * @param string $boolean
     * @param \Closure|null $callback
     * @return $this|BaseSearch
     */
    public function has(
        $relation,
        $operator = '>=',
        $count = 1,
        $boolean = 'and',
        \Closure $callback = null
    ): BaseSearch {
        $this->eloquentBuilder->has($relation, $operator, $count, $boolean, $callback);

        return $this;
    }

    /**
     * @param $result
     * @return BaseEntity|BaseEntity[]|null
     */
    protected function toDto($result)
    {
        return $this->converter->fromModelOrCollection($result);
    }
}
