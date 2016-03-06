<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-08
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

/**
 * Class UniqueNumberStorageFactory
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
class UniqueNumberStorageFactory extends AbstractStorageFactory
{
    /**
     * @return UniqueNumberStorage
     */
    protected function getStorage()
    {
        return new UniqueNumberStorage();
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return 'unique_number';
    }
}
