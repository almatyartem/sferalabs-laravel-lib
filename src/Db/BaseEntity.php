<?php

namespace LaravelSferaTemplate\Db;

use LaravelSferaTemplate\Db\Relations\BaseRelation;
use LaravelSferaTemplate\Db\Relations\BelongsToManyRelation;
use LaravelSferaTemplate\Db\Relations\BelongsToRelation;
use LaravelSferaTemplate\Db\Relations\HasManyRelation;
use LaravelSferaTemplate\Db\Relations\HasOneRelation;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\FieldValidator;
use Spatie\DataTransferObject\ValueCaster;

abstract class BaseEntity extends DataTransferObject
{
    /**
     * @var bool
     */
    protected bool $ignoreMissing = true;

    /**
     * @return string
     */
    abstract public static function getTable() : string;

    /**
     * @return array
     */
    abstract public static function getRules() : array;

    /**
     * @return bool
     */
    public static function useTimeStamps() : bool
    {
        return false;
    }

    /**
     * @return array
     */
    public static function getRelations() : array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function getHidden() : array
    {
        return [
            'updated_at',
            'created_at',
        ];
    }

    /**
     * @param array $data
     * @return BaseEntity|mixed
     */
    public static function fromArray(array $data) : BaseEntity
    {
        $DTO = new static();

        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                $DTO->{$key} = $value;
            }
        }
        return $DTO;
    }

    /**
    * @param string $entityClassName
     * @param string $name
     * @return BelongsToRelation|HasOneRelation|HasManyRelation|BelongsToManyRelation|null
     */
    public static function getRelation(string $entityClassName, string $name) : ?BaseRelation
    {
        /**
         * @var $entityClassName BaseEntity
         */
        return $entityClassName::getRelations()[$name] ?? null;
    }

    /**
     * @param ValueCaster $valueCaster
     * @param FieldValidator $fieldValidator
     * @param mixed $value
     * @return mixed
     */
    protected function castValue(ValueCaster $valueCaster, FieldValidator $fieldValidator, $value)
    {
        if(count($fieldValidator->allowedTypes) == 1){
            $allowedType = $fieldValidator->allowedTypes[0];

            if($allowedType == 'integer' and is_string($value) and is_numeric($value)){
                return (int)$value;
            }
            if($allowedType == 'boolean' and is_numeric($value)){
                return (bool)$value;
            }
        }

        return parent::castValue($valueCaster, $fieldValidator, $value);
    }
}