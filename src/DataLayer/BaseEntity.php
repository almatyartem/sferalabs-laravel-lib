<?php

namespace LaravelSferaLibrary\DataLayer;

use LaravelSferaLibrary\DataLayer\Relations\BaseRelation;
use LaravelSferaLibrary\DataLayer\Relations\BelongsToManyRelation;
use LaravelSferaLibrary\DataLayer\Relations\BelongsToRelation;
use LaravelSferaLibrary\DataLayer\Relations\HasManyRelation;
use LaravelSferaLibrary\DataLayer\Relations\HasOneRelation;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\DataTransferObjectError;
use Spatie\DataTransferObject\FieldValidator;
use Spatie\DataTransferObject\ValueCaster;

abstract class BaseEntity extends DataTransferObject
{
     public function __construct(array $parameters = [])
    {
        $validators = $this->getFieldValidators();

        $valueCaster = $this->getValueCaster();

        /** string[] */
        $invalidTypes = [];

        foreach ($validators as $field => $validator) {
            if (
                ! isset($parameters[$field])
                && ! $validator->hasDefaultValue
                && ! $validator->isNullable
            ) {
                throw DataTransferObjectError::uninitialized(
                    static::class,
                    $field
                );
            }

            $value = $parameters[$field] ?? $this->{$field} ?? null;

            $value = $this->castValue($valueCaster, $validator, $value);

            if (! $validator->isValidType($value) and !is_array($value)) {
                $invalidTypes[] = DataTransferObjectError::invalidTypeMessage(
                    static::class,
                    $field,
                    $validator->allowedTypes,
                    $value
                );

                continue;
            }

            $this->{$field} = $value;

            unset($parameters[$field]);
        }

        if ($invalidTypes) {
            DataTransferObjectError::invalidTypes($invalidTypes);
        }

        if (! $this->ignoreMissing && count($parameters)) {
            throw DataTransferObjectError::unknownProperties(array_keys($parameters), static::class);
        }
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * @var bool
     */
    protected bool $ignoreMissing = true;

    /**
     * @return string
     */
    abstract public static function getTable(): string;

    /**
     * @param int|null $id
     * @return array
     */
    abstract public static function getRules(int $id = null): array;

    /**
     * @return string|null
     */
    public static function getUpdatedAtColumnName() : ?string
    {
        return property_exists(static::class, 'updated_at') ? 'updated_at' : null;
    }

    /**
     * @return string|null
     */
    public static function getCreatedAtColumnName() : ?string
    {
        return property_exists(static::class, 'created_at') ? 'created_at' : null;
    }

    /**
     * @return array
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function getHidden(): array
    {
        return [];
    }

    /**
     * @param array $data
     * @return BaseEntity|mixed
     */
    public static function fromArray(array $data): BaseEntity
    {
        return new static($data);
    }

    /**
     * @param string $entityClassName
     * @param string $name
     * @return BelongsToRelation|HasOneRelation|HasManyRelation|BelongsToManyRelation|null
     */
    public static function getRelation(string $entityClassName, string $name): ?BaseRelation
    {
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
        if (count($fieldValidator->allowedTypes) == 1) {
            $allowedType = $fieldValidator->allowedTypes[0];

            if ($allowedType == 'integer' and is_string($value) and is_numeric($value)) {
                return (int)$value;
            }
            if ($allowedType == 'boolean' and is_numeric($value)) {
                return (bool)$value;
            }
        }

        $result = parent::castValue($valueCaster, $fieldValidator, $value);

        return $result;
    }
}
