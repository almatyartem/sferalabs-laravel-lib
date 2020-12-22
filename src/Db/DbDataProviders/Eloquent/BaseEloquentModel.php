<?php

namespace LaravelSferaTemplate\Db\DbDataProviders\Eloquent;

use LaravelSferaTemplate\Db\BaseEntity;
use LaravelSferaTemplate\Db\Relations\BaseRelation;
use LaravelSferaTemplate\Db\Relations\BelongsToManyRelation;
use LaravelSferaTemplate\Db\Relations\BelongsToRelation;
use LaravelSferaTemplate\Db\Relations\HasManyRelation;
use LaravelSferaTemplate\Db\Relations\HasOneRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;

class BaseEloquentModel extends Model
{
    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    protected string $entityClass;

    /**
     * BaseEloquentModel constructor.
     * @param string $entityClass
     * @param array $attributes
     */
    public function __construct(string $entityClass, array $attributes = [])
    {
        $this->entityClass = $entityClass;

        $this->EMConstruct($attributes);
    }

    /**
     * @param array $attributes
     */
    public function EMConstruct(array $attributes = [])
    {
        $this->bootIfNotBooted();

        $this->initializeTraits();

        $this->syncOriginal();

        $this->forceFill($attributes);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return ExtendedBuilder
     */
    public function newEloquentBuilder($query) : ExtendedBuilder
    {
        return new ExtendedBuilder($query);
    }

    /**
     * @return string
     */
    public function getTable() : string
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $this->entityClass;

        return $entityClassName::getTable();
    }

    /**
     * Determine if the model uses timestamps.
     *
     * @return bool
     */
    public function usesTimestamps() : bool
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $this->entityClass;

        return $entityClassName::useTimeStamps();
    }

    /**
     * @return array
     */
    public function getHidden() : array
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $this->entityClass;

        return $entityClassName::getHidden();
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return BelongsTo|BelongsToMany|HasMany|Relation|mixed|null
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {
        if ($relation = $this->getRelationData($method)) {
            return $this->makeRelation($relation);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * @param $relation
     * @return BelongsTo|BelongsToMany|HasMany|null
     * @throws \Exception
     */
    protected function makeRelation($relation) : ?Relation
    {
        switch (get_class($relation)) {
            case BelongsTo::class:
                return $this->makeBelongsToRelation($relation);
            case HasManyRelation::class:
                return $this->makeHasManyRelation($relation);
            case BelongsToManyRelation::class:
                return $this->makeBelongsToManyRelation($relation);
            case HasOneRelation::class:
                return $this->makeHasOneRelation($relation);
        }

        return null;
    }

    /**
     * @param BelongsToRelation $relation
     * @return BelongsTo
     */
    protected function makeBelongsToRelation(BelongsToRelation $relation) : BelongsTo
    {
        return $this->belongsTo($relation->entity, $relation->foreignKey);
    }

    /**
     * @param HasManyRelation $relation
     * @return HasMany
     */
    protected function makeHasManyRelation(HasManyRelation $relation) : HasMany
    {
        return $this->hasMany($relation->entity, $relation->foreignKey);
    }

    /**
     * @param HasOneRelation $relation
     * @return HasOne
     */
    protected function makeHasOneRelation(HasOneRelation $relation) : HasOne
    {
        return $this->hasOne($relation->entity, $relation->foreignKey);
    }

    /**
     * @param BelongsToManyRelation $relation
     * @return BelongsToMany
     * @throws \Exception
     */
    protected function makeBelongsToManyRelation(BelongsToManyRelation $relation) : BelongsToMany
    {
        return $this->belongsToMany(
            $relation->entity,
            $relation->table,
            $relation->foreignPivotKey,
            $relation->relatedPivotKey,
            $relation->parentKey,
            $relation->relatedKey
        );
    }

    /**
     * @param string $relation
     * @return BaseRelation|null
     * @throws \Exception
     */
    protected function getRelationData(string $relation) : ?BaseRelation
    {
        /**
         * @var $entityClassName BaseEntity
         */
        $entityClassName = $this->entityClass;

        return $entityClassName::getRelation($this->entityClass, $relation);
    }

    protected function newRelatedInstance($class)
    {
        return tap(new self($class), function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->connection);
            }
        });
    }

    /**
     * @param array $attributes
     * @param false $exists
     * @return $this|BaseEloquentModel
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = new static($this->entityClass, (array) $attributes);
        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        $model->setTable($this->getTable());

        return $model;
    }

    /**
     * @return string
     */
    public function getEntityClassName() : string
    {
        return $this->entityClass;
    }
}
