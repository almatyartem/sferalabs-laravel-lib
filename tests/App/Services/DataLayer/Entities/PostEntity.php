<?php

namespace Tests\App\Services\DataLayer\Entities;

use Illuminate\Validation\Rule;
use LaravelSferaLibrary\DataLayer\BaseEntity;
use LaravelSferaLibrary\DataLayer\Relations\RelationsFactory;

/**
 * Class OnlyForTestEntity
 * @package Tests\App\Services\DataLayer\Entities
 */
class PostEntity extends BaseEntity
{
    const ENUM_OPTIONS = ['one', 'two'];
    const RELATION_USER = 'user';

    public ?int $id;
    public ?int $user_id;
    public string $unique_string_example;
    public string $string_example;
    public int $int_example;
    public string $enum_example;
    public ?string $updated_at;
    public ?string $created_at;

    public ?UserEntity $user;

    /**
     * @return string the associated database table name
     */
    public static function getTable(): string
    {
        return 'only_for_tests_posts';
    }

    /**
     * @param int|null $id
     * @return array
     */
    public static function getRules(int $id = null): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:' . UserEntity::getTable() . ',id'],
            'unique_string_example' => ['required', 'max:255', Rule::unique(self::getTable())->ignore($id)],
            'string_example' => ['required', 'max:255'],
            'int_example' => ['required', 'integer'],
            'enum_example' => ['required', 'in:'.implode(',', self::ENUM_OPTIONS)]
        ];
    }

    /**
     * @return array relational rules.
     */
    public static function getRelations(): array
    {
        return [
            self::RELATION_USER => RelationsFactory::belongsTo(
                UserEntity::class,
                'user_id'
            ),
        ];
    }
}
