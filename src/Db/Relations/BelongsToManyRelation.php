<?php

namespace LaravelSferaTemplate\Db\Relations;

class BelongsToManyRelation extends BaseRelation
{
    public string $entity;
    public string $table;
    public string $foreignPivotKey;
    public string $relatedPivotKey;
    public ?string $parentKey;
    public ?string $relatedKey;

    /**
     * BelongsToManyRelation constructor.
     * @param string $entity
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string|null $parentKey
     * @param string|null $relatedKey
     */
    function __construct(string $entity, string $table, string $foreignPivotKey, string $relatedPivotKey,
                         string $parentKey = null, string $relatedKey = null)
    {
        $this->entity = $entity;
        $this->table = $table;
        $this->foreignPivotKey = $foreignPivotKey;
        $this->relatedPivotKey = $relatedPivotKey;
        $this->parentKey = $parentKey;
        $this->relatedKey = $relatedKey;
    }
}
