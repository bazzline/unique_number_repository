<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-08
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

use Net\Bazzline\Component\Database\FileStorage\Storage\Storage;
use Net\Bazzline\Component\Database\FileStorage\Storage\StorageFactory;
use Net\Bazzline\Component\Locator\FactoryInterface;
use Net\Bazzline\Component\Locator\LocatorInterface;
use Net\Bazzline\UniqueNumberRepository\Application\Service\ApplicationLocator;

/**
 * Class AbstractStorageFactory
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
abstract class AbstractStorageFactory extends StorageFactory implements FactoryInterface
{
    /** @var ApplicationLocator */
    private $locator;

    /**
     * @param LocatorInterface $locator
     * @return $this
     */
    public function setLocator(LocatorInterface $locator)
    {
        $this->locator = $locator;

        return $this;
    }

    /**
     * @return mixed|Storage
     */
    public function create()
    {
        $storage = parent::create();
        $storage->injectPath('data/' . $this->getRepositoryName());

        return $storage;
    }

    /**
     * @return string
     */
    abstract protected function getRepositoryName();
}
