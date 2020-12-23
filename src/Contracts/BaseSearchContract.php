<?php

namespace LaravelSferaLibrary\Contracts;

interface BaseSearchContract
{
    /**
     * @return mixed|null
     */
    public function find();

    /**
     * @return mixed|null
     */
    public function first();

    /**
     * @param string[] $columns
     * @return array
     */
    public function firstAsArray($columns = ['*']) : array;

    /**
     * @param string[] $columns
     * @return array
     */
    public function findAsArray($columns = ['*']) : array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $value
     * @return BaseSearchContract
     */
    public function limit(int $value) : BaseSearchContract;

    /**
     * @param int $value
     * @return BaseSearchContract
     */
    public function offset(int $value) : BaseSearchContract;
}
