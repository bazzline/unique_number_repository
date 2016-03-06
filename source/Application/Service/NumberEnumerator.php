<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-08
 */
namespace Net\Bazzline\UniqueNumberRepository\Application\Service;

use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;
use Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage\UniqueNumberStorage;

class NumberEnumerator
{
    /**
     * @var UniqueNumberStorage
     */
    private $storage;

    /**
     * @param UniqueNumberStorage $storage
     */
    public function __construct(UniqueNumberStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $repositoryName
     * @return int
     */
    public function increment($repositoryName)
    {
        //begin of dependencies
        $storage        = $this->storage;
        //end of dependencies

        //begin of process
        $collection     = $this->fetchCollection($storage, $repositoryName);
        $numbers        = $this->fetchNumbersFromCollection($collection);
        $orderedNumbers = $this->orderNumbers($numbers);
        $freeNumber     = $this->calculateFreeNumber($orderedNumbers);

        return $freeNumber;
        //end of process
    }

    /**
     * @param UniqueNumberStorage $storage
     * @param string $name
     * @return array|\Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest[]
     */
    private function fetchCollection(UniqueNumberStorage $storage, $name)
    {
        $storage->filterByRepositoryName($name);

        return $storage->readMany();
    }

    /**
     * @param array $collection
     * @return array
     */
    private function fetchNumbersFromCollection(array $collection)
    {
        $numbers = array();

        foreach ($collection as $request) {
            /** @var UniqueNumberRequest $request */
            $numbers[] = $request->number();
        }

        return $numbers;
    }

    /**
     * @param array $numbers
     * @return array
     */
    private function orderNumbers(array $numbers)
    {
        natsort($numbers);

        return $numbers;
    }

    /**
     * @param array $numbers
     * @return int
     */
    private function calculateFreeNumber(array $numbers)
    {
        $currentMaximum = 0;

        foreach ($numbers as $number) {
            $numberIsHigherThanCurrentNumber = ($number > $currentMaximum);

            if ($numberIsHigherThanCurrentNumber) {
                $numberIsHigherThanCurrentNumberPlusOne = ($number > ($currentMaximum + 1));

                if ($numberIsHigherThanCurrentNumberPlusOne) {
                    break;
                }

                $currentMaximum = $number;
            }
        }

        return (++$currentMaximum);
    }
}
