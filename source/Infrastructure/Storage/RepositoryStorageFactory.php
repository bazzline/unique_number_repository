<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

/**
 * Class RepositoryStorageFactory
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
class RepositoryStorageFactory extends AbstractStorageFactory
{
    /**
     * @return RepositoryStorage
     */
    protected function getStorage()
    {
        return new RepositoryStorage();
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return 'repository';
    }
}
