<?php

namespace Tests\App\Services\DataLayer\Entities;

use LaravelSferaLibrary\DataLayer\BaseEntity;

/**
 * Class UserEntity
 * @package App\Services\DataLayer\Entities
 */
class UserEntity extends BaseEntity
{
    public int $id;
    public string $email;

    /**
     * @return string
     */
    public static function getTable(): string
    {
        return 'only_for_tests_users';
    }

    /**
     * @return string[]
     */
    public static function getHidden(): array
    {
        return [
            'password'
        ];
    }

    public static function getRules(int $id = null): array
    {
        return [];
    }
}
