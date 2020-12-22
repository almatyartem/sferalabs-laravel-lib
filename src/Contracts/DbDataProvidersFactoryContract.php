<?php

namespace LaravelSferaTemplate\Contracts;

interface DbDataProvidersFactoryContract
{
    /**
     * @param string $entityClass
     * @return DbDataProviderContract
     */
    public function getDataProviderByEntityClass(string $entityClass) : DbDataProviderContract;
}
