<?php

namespace Tests\App\Services\DataLayer\Search;

use LaravelSferaLibrary\DataLayer\BaseSearch;
use Tests\App\Services\DataLayer\Entities\PostEntity;

/**
 * Class OnlyForTestSearch
 * @package Tests\App\Services\DataLayer\Search
 */
class PostSearch extends BaseSearch
{
    /**
     * @param int $id
     * @return PostSearch
     */
    public function byId(int $id) : PostSearch
    {
        return $this->whereEqual('id', $id);
    }

    /**
     * @return PostSearch
     */
    public function withUser() : PostSearch
    {
        return $this->with(PostEntity::RELATION_USER);
    }
}
