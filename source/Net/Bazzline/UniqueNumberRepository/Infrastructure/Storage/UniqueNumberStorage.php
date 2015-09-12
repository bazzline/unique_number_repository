<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-12
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;

/**
 * Class UniqueNumberStorage
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
class UniqueNumberStorage extends AbstractStorage
{
    CONST KEY_APPLICANT_NAME    = 'applicant_name';
    CONST KEY_NUMBER            = 'number';
    CONST KEY_OCCURRED_ON       = 'occurred_on';
    CONST KEY_REPOSITORY_NAME   = 'repository_name';

    //begin of overridden methods
    /**
     * @param bool|true $resetRuntimeProperties
     * @return array|UniqueNumberRequest[]
     */
    public function readMany($resetRuntimeProperties = true)
    {
        $collection = array();
        $content    = parent::readMany($resetRuntimeProperties);

        foreach ($content as $data) {
            $collection[] = new UniqueNumberRequest(
                $data[self::KEY_APPLICANT_NAME],
                $data[self::KEY_NUMBER],
                $this->createDateTimeFromTimestamp($data[self::KEY_OCCURRED_ON]),
                $data[self::KEY_REPOSITORY_NAME]
            );
        }

        return $collection;
    }
    //end of overridden methods

    /**
     * @param int $number
     * @return $this
     */
    public function filterByNumber($number)
    {
        return $this->filterBy(self::KEY_NUMBER, $number);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function filterByRepositoryName($name)
    {
        return $this->filterBy(self::KEY_REPOSITORY_NAME, $name);
    }

    /**
     * @param UniqueNumberRequest $request
     * @param bool|false $resetRuntimeProperties
     * @return string
     */
    public function createFrom(UniqueNumberRequest $request, $resetRuntimeProperties = false)
    {
        return $this->create(
            array (
                self::KEY_APPLICANT_NAME    => $request->applicantName(),
                self::KEY_NUMBER            => $request->number(),
                self::KEY_OCCURRED_ON       => $request->occurredOn()->getTimestamp(),
                self::KEY_REPOSITORY_NAME   => $request->repositoryName()
            ),
            $resetRuntimeProperties
        );
    }
}