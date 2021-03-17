<?php

namespace LaravelSferaLibrary\DataLayer\DbDataProviders\Eloquent;

use Illuminate\Support\Collection;
use LaravelSferaLibrary\DataLayer\BaseEntity;

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
        $entityClassName = $record->getEntityClassName();
        $attributes = $record->attributesToArray();
        $relations = self::getRelations($record);
        $data = array_merge($attributes, $relations);

        $entityData = call_user_func([$entityClassName, 'fromArray'], $data);

        foreach ($relations as $key => $value) {
          $entityData->{$key} = $value;
        }

        return $entityData;
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
