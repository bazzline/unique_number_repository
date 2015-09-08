<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-08
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

class UniqueNumberRepositoryFactory extends AbstractRepositoryFactory
{
    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return 'unique_number';
    }
}
