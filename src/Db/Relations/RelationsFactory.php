<?php

namespace LaravelSferaLibrary\Db\Relations;

class RelationsFactory
{
    /**
     * @param string $entity
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string|null $parentKey
     * @param string|null $relatedKey
     * @return BelongsToManyRelation
     */
    public static function belongsToMany(string $entity, string $table, string $foreignPivotKey, string $relatedPivotKey,
                         string $parentKey = null, string $relatedKey = null) : BelongsToManyRelation
    {
        return new BelongsToManyRelation($entity, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey);
    }

    /**
     * @param string $entity
     * @param string $foreignKey
     * @return BelongsToRelation
     */
    public static function belongsTo(string $entity, string $foreignKey) : BelongsToRelation
    {
        return new BelongsToRelation($entity, $foreignKey);
    }

    /**
     * @param string $entity
     * @param string $foreignKey
     * @return HasManyRelation
     */
    public static function hasMany(string $entity, string $foreignKey) : HasManyRelation
    {
        return new HasManyRelation($entity, $foreignKey);
    }

    /**
     * @param string $entity
     * @param string $foreignKey
     * @return HasOneRelation
     */
    public static function hasOne(string $entity, string $foreignKey) : HasOneRelation
    {
        return new HasOneRelation($entity, $foreignKey);
    }
}
