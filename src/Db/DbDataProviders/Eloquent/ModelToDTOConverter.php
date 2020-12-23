<?php

namespace LaravelSferaLibrary\Db\DbDataProviders\Eloquent;

use LaravelSferaLibrary\Db\BaseEntity;
use Illuminate\Support\Collection;

class ModelToDTOConverter
{
    /**
     * @param $data
     * @return BaseEntity|BaseEntity[]|null
     */
    public static function fromModelOrCollection($data)
    {
        if ($data instanceof BaseEloquentModel) {
            return self::fromEloquent($data);
        } elseif ($data instanceof Collection) {
            return self::fromCollection($data);
        }

        return null;
    }

    /**
     * @param BaseEloquentModel $record
     * @return BaseEntity
     */
    public static function fromEloquent(BaseEloquentModel $record): BaseEntity
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $record->getEntityClassName();
        $attributes = $record->attributesToArray();
        $relations = self::getRelations($record);
        $data = array_merge($attributes, $relations);

        return $entityClassName::fromArray($data);
    }

    /**
     * @param Collection $collection
     * @return BaseEntity[]
     */
    public static function fromCollection(Collection $collection): array
    {
        $result = [];
        foreach ($collection as $model) {
            $result[] = self::fromEloquent($model);
        }

        return $result;
    }

    /**
     * @param BaseEloquentModel $record
     * @return array
     */
    protected static function getRelations(BaseEloquentModel $record) : array
    {
        $relations = $record->getRelations();
        $result = [];

        foreach ($relations as $k => $v) {
            $result[$k] = self::fromModelOrCollection($v);
        }

        return $result;
    }
}
