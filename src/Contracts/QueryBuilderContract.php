<?php

namespace LaravelSferaTemplate\Contracts;

interface QueryBuilderContract
{
    public function orderBy($column, $direction = 'asc');

    public function groupBy(...$groups);

    public function where($column, $operator = null, $value = null, $boolean = 'and');

    public function whereIn($column, $values, $boolean = 'and', $not = false);

    public function whereNotIn($column, $values, $boolean = 'and');

    public function groupWhere($group, $boolean = 'and');

    public function limit($value);

    public function offset($value);

    public function with($relations, $callback = null);

    public function whereHas($relation, \Closure $callback = null, $operator = '>=', $count = 1);

    public function whereDoesntHave($relation, \Closure $callback = null);

    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', \Closure $callback = null);

    public function get($columns = ['*']);

    public function first($columns = ['*']);

    public function count() : int;
}
