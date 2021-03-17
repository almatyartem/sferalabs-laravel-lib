<?php

namespace LaravelSferaLibrary\DataLayer\DbDataProviders\Eloquent;

use LaravelSferaLibrary\DataLayer\Relations\BaseRelation;
use LaravelSferaLibrary\DataLayer\Relations\BelongsToManyRelation;
use LaravelSferaLibrary\DataLayer\Relations\BelongsToRelation;
use LaravelSferaLibrary\DataLayer\Relations\HasManyRelation;
use LaravelSferaLibrary\DataLayer\Relations\HasManyThroughRelation;
use LaravelSferaLibrary\DataLayer\Relations\HasOneRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
     * @param array $attributes
     * @param string $entityClass
     */
    public function __construct(array $attributes = [], string $entityClass = '')
    {
        $this->entityClass = $entityClass;

        parent::__construct($attributes);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTable() : string
    {
        return $this->callEntity('getTable');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getHidden() : array
    {
        return $this->callEntity('getHidden');
    }

    /**
     * @return string
     */
    public function getEntityClassName() : string
    {
        return $this->entityClass;
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
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    protected function callEntity(string $method, array $parameters = [])
    {
        return call_user_func([$this->entityClass, $method], ...$parameters);
    }

    /**
     * @param $relation
     * @return BelongsTo|BelongsToMany|HasMany|null
     * @throws \Exception
     */
    protected function makeRelation($relation) : ?Relation
    {
        switch (get_class($relation)) {
            case BelongsToRelation::class:
                return $this->makeBelongsToRelation($relation);
            case HasManyRelation::class:
                return $this->makeHasManyRelation($relation);
            case BelongsToManyRelation::class:
                return $this->makeBelongsToManyRelation($relation);
            case HasOneRelation::class:
                return $this->makeHasOneRelation($relation);
            case HasManyThroughRelation::class:
                return $this->makeHasManyThroughRelation($relation);
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
        return $this->hasOne($relation->entity, $relation->foreignKey, $relation->localKey);
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
     * @param HasManyThroughRelation $relation
     * @return HasManyThrough
     * @throws \Exception
     */
    protected function makeHasManyThroughRelation(HasManyThroughRelation $relation) : HasManyThrough
    {
        return $this->hasManyThrough(
            $relation->related,
            $relation->through,
            $relation->firstKey,
            $relation->secondKey,
            $relation->localKey,
            $relation->secondLocalKey
        );
    }

    /**
     * @param string $relation
     * @return BaseRelation|null
     * @throws \Exception
     */
    protected function getRelationData(string $relation) : ?BaseRelation
    {
        return $this->callEntity('getRelation', [$this->entityClass,$relation]);
    }

    protected function newRelatedInstance($class)
    {
        return tap(new self([], $class), function ($instance) {
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
        $model = new static((array) $attributes, $this->entityClass);
        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        $model->setTable($this->getTable());

        return $model;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return (new static)->newQuery();
    }
}
