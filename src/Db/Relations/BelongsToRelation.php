<?php

namespace LaravelSferaTemplate\Db\Relations;

class BelongsToRelation extends BaseRelation
{
    public string $entity;
    public string $foreignKey;

    /**
     * BelongsToRelation constructor.
     * @param string $entity
     * @param string $foreignKey
     */
    function __construct(string $entity, string $foreignKey)
    {
        $this->entity = $entity;
        $this->foreignKey = $foreignKey;
    }
}
