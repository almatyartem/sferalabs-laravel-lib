<?php

namespace LaravelSferaLibrary\Db\DbDataProviders\Eloquent;

use LaravelSferaLibrary\Contracts\DbDataProvidersFactoryContract;

class EloquentDbDataProvidersFactory implements DbDataProvidersFactoryContract
{
    /**
     * @param string $entityClass
     * @return EloquentDbDataProvider
     * @throws \Exception
     */
    public function getDataProviderByEntityClass(string $entityClass) : EloquentDbDataProvider
    {
        return new EloquentDbDataProvider($entityClass);
    }
}
