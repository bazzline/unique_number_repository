<?php
/**
 * @author Net\Bazzline\Component\Locator
 * @since 2015-09-08
 */

namespace Net\Bazzline\UniqueNumberRepository\Application\Service;

use Net\Bazzline\Component\Locator\FactoryInterface;

/**
 * Class ApplicationLocator
 *
 * @package Net\Bazzline\UniqueNumberRepository\Application\Service
 */
class ApplicationLocator implements \Net\Bazzline\Component\Locator\LocatorInterface
{
    /**
     * @var $factoryInstancePool
     */
    private $factoryInstancePool = array();

    /**
     * @var $sharedInstancePool
     */
    private $sharedInstancePool = array();

    /**
     * @return \Net\Bazzline\Component\Database\FileStorage\RepositoryFactory
     */
    public function getRepositoryFactory()
    {
        return $this->fetchFromSharedInstancePool('\Net\Bazzline\Component\Database\FileStorage\RepositoryFactory');
    }

    /**
     * @return \Net\Bazzline\Component\Database\FileStorage\Repository
     */
    public function getRepositoryRepository()
    {
        $className = '\Net\Bazzline\Component\Database\FileStorage\Repository';

        if ($this->isNotInSharedInstancePool($className)) {
            $factoryClassName = '\Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage\RepositoryRepositoryFactory';
            $factory = $this->fetchFromFactoryInstancePool($factoryClassName);
            
            $this->addToSharedInstancePool($className, $factory->create());
        }

        return $this->fetchFromSharedInstancePool($className);
    }

    /**
     * @return \Net\Bazzline\Component\Database\FileStorage\Repository
     */
    public function getUniqueNumberRepository()
    {
        $className = '\Net\Bazzline\Component\Database\FileStorage\Repository';

        if ($this->isNotInSharedInstancePool($className)) {
            $factoryClassName = '\Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage\UniqueNumberRepositoryFactory';
            $factory = $this->fetchFromFactoryInstancePool($factoryClassName);
            
            $this->addToSharedInstancePool($className, $factory->create());
        }

        return $this->fetchFromSharedInstancePool($className);
    }

    /**
     * @return \Net\Bazzline\UniqueNumberRepository\Application\Service\NumberEnumerator
     */
    public function getUniqueNumberEnumerator()
    {
        $className = '\Net\Bazzline\UniqueNumberRepository\Application\Service\NumberEnumerator';

        if ($this->isNotInSharedInstancePool($className)) {
            $factoryClassName = '\Net\Bazzline\UniqueNumberRepository\Application\Service\UniqueNumberEnumeratorFactory';
            $factory = $this->fetchFromFactoryInstancePool($factoryClassName);
            
            $this->addToSharedInstancePool($className, $factory->create());
        }

        return $this->fetchFromSharedInstancePool($className);
    }

    /**
     * @param string $className
     * @return FactoryInterface
     * @throws InvalidArgumentException
     */
    final protected function fetchFromFactoryInstancePool($className)
    {
        if ($this->isNotInFactoryInstancePool($className)) {
            if (!class_exists($className)) {
                throw new InvalidArgumentException(
                    'factory class "' . $className . '" does not exist'
                );
            }
            
            /** @var FactoryInterface $factory */
            $factory = new $className();
            $factory->setLocator($this);
            $this->addToFactoryInstancePool($className, $factory);
        }

        return $this->getFromFactoryInstancePool($className);
    }

    /**
     * @param string $className
     * @param FactoryInterface $factory
     * @return $this
     */
    private function addToFactoryInstancePool($className, FactoryInterface $factory)
    {
        $this->factoryInstancePool[$className] = $factory;

        return $this;
    }

    /**
     * @param string $className
     * @return null|FactoryInterface
     */
    private function getFromFactoryInstancePool($className)
    {
        return $this->factoryInstancePool[$className];
    }

    /**
     * @param string $className
     * @return boolean
     */
    private function isNotInFactoryInstancePool($className)
    {
        return (!isset($this->factoryInstancePool[$className]));
    }

    /**
     * @param string $className
     * @return object
     * @throws InvalidArgumentException
     */
    final protected function fetchFromSharedInstancePool($className)
    {
        if ($this->isNotInSharedInstancePool($className)) {
            if (!class_exists($className)) {
                throw new InvalidArgumentException(
                    'class "' . $className . '" does not exist'
                );
            }
            
            $instance = new $className();
            $this->addToSharedInstancePool($className, $instance);
        }

        return $this->getFromSharedInstancePool($className);
    }

    /**
     * @param string $className
     * @param object $instance
     * @return $this
     */
    private function addToSharedInstancePool($className, $instance)
    {
        $this->sharedInstancePool[$className] = $instance;

        return $this;
    }

    /**
     * @param string $className
     * @return null|object
     */
    private function getFromSharedInstancePool($className)
    {
        return $this->sharedInstancePool[$className];
    }

    /**
     * @param string $className
     * @return boolean
     */
    private function isNotInSharedInstancePool($className)
    {
        return (!isset($this->sharedInstancePool[$className]));
    }
}