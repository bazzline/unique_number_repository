<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-08
 */
namespace Net\Bazzline\UniqueNumberRepository\Application\Service;

use DateTime;
use Net\Bazzline\Component\Database\FileStorage\Storage\Storage;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;

class NumberEnumerator
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $repositoryName
     * @return int
     */
    public function increment($repositoryName)
    {
        $currentMaximum = 0;
        $storage        = $this->storage;
        $storage->filterBy('repository_name', $repositoryName);
        $collection     = $storage->readMany();

        foreach ($collection as $data) {
            //@todo replace by injected hydrator
            $request                = $this->hydrate($data);
            $requestHasHigherNumber = ($request->number() > $currentMaximum);

            if ($requestHasHigherNumber) {
                $currentMaximum = $request->number();
            }
        }

        return (++$currentMaximum);
    }

    /**
     * @param array $data
     * @return UniqueNumberRequest
     */
    private function hydrate(array $data)
    {
        return new UniqueNumberRequest(
            $data['applicant_name'],
            $data['number'],
            new DateTime(date('Y-m-d H:i:s', $data['occurred_on'])),
            $data['repository_name']
        );
    }
}
