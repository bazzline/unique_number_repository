<?php

return array(
    'assembler'     => '\Net\Bazzline\Component\Locator\Configuration\Assembler\FromArrayAssembler',
    'class_name'    => 'ApplicationLocator',
    'file_exists_strategy' => '\Net\Bazzline\Component\Locator\FileExistsStrategy\DeleteStrategy',
    'file_path'     => __DIR__ . '/../source/Net/Bazzline/UniqueNumberRepository/Application/Service',
    'implements'    => array(
        '\Net\Bazzline\Component\Locator\LocatorInterface'
    ),
    'instances' => array(
        array(
            'alias'         => 'RepositoryFactory',
            'class_name'    => '\Net\Bazzline\Component\Database\FileStorage\RepositoryFactory',
            'is_factory'    => false,
            'is_shared'     => true
        ),
        array(
            'alias'         => 'RepositoryRepository',
            'class_name'    => '\Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage\RepositoryRepositoryFactory',
            'is_factory'    => true,
            'is_shared'     => true,
            'return_value'  => '\Net\Bazzline\Component\Database\FileStorage\Repository'
        ),
        array(
            'alias'         => 'UniqueNumberRepository',
            'class_name'    => '\Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage\UniqueNumberRepositoryFactory',
            'is_factory'    => true,
            'is_shared'     => true,
            'return_value'  => '\Net\Bazzline\Component\Database\FileStorage\Repository'
        ),
        array(
            'alias'         => 'UniqueNumberEnumerator',
            'class_name'    => '\Net\Bazzline\UniqueNumberRepository\Application\Service\UniqueNumberEnumeratorFactory',
            'is_factory'    => true,
            'is_shared'     => true,
            'return_value'  => '\Net\Bazzline\UniqueNumberRepository\Application\Service\NumberEnumerator'
        )
        /*
        array(
            'alias'         => 'CliProgressBar',
            'class_name'    => '\Net\Bazzline\Component\Cli\ProgressBar\ProgressBar',
            'is_factory'    => false,
            'is_shared'     => true
        ),
        */
    ),
    'method_prefix' => 'get',
    'namespace'     => 'Net\Bazzline\UniqueNumberRepository\Application\Service',
);
