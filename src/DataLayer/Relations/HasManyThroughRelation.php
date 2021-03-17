<?php

namespace LaravelSferaLibrary\DataLayer\Relations;

class HasManyThroughRelation extends BaseRelation
{
    public string $related;
    public string  $through;
    public ?string  $firstKey;
    public ?string  $secondKey;
    public ?string  $localKey;
    public ?string  $secondLocalKey;

    /**
     * HasManyThroughRelation constructor.
     * @param string $related
     * @param string $through
     * @param string|null $firstKey
     * @param string|null $secondKey
     * @param string|null $localKey
     * @param string|null $secondLocalKey
     */
    function __construct(
        string $related,
        string $through,
        string $firstKey = null,
        string $secondKey = null,
        string $localKey = null,
        string $secondLocalKey = null
    ) {
        $this->related = $related;
        $this->through = $through;
        $this->firstKey = $firstKey;
        $this->secondKey = $secondKey;
        $this->localKey = $localKey;
        $this->secondLocalKey = $secondLocalKey;
    }
}
