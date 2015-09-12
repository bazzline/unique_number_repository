<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-12
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

use Net\Bazzline\UniqueNumberRepository\Domain\Model\RepositoryRequest;

/**
 * Class RepositoryStorage
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
class RepositoryStorage extends AbstractStorage
{
    const KEY_APPLICANT_NAME    = 'applicant_name';
    const KEY_NAME              = 'name';
    CONST KEY_OCCURRED_ON       = 'occurred_on';

    //begin of overridden methods
    /**
     * @param bool|true $resetRuntimeProperties
     * @return array|RepositoryRequest[]
     */
    public function readMany($resetRuntimeProperties = true)
    {
        $collection = array();
        $content    = parent::readMany($resetRuntimeProperties);

        foreach ($content as $data) {
            $collection[] = new RepositoryRequest(
                $data[self::KEY_APPLICANT_NAME],
                $data[self::KEY_NAME],
                $this->createDateTimeFromTimestamp($data[self::KEY_OCCURRED_ON])
            );
        }

        return $collection;
    }
    //end of overridden methods

    /**
     * @param string $name
     * @return $this
     */
    public function filterByName($name)
    {
        return $this->filterBy(self::KEY_NAME, $name);
    }

    /**
     * @param RepositoryRequest $request
     * @param bool|false $resetRuntimeProperties
     * @return string
     */
    public function createFrom(RepositoryRequest $request, $resetRuntimeProperties = false)
    {
        return $this->create(
            array(
                self::KEY_APPLICANT_NAME    => $request->applicantName(),
                self::KEY_NAME              => $request->name(),
                self::KEY_OCCURRED_ON       => $request->occurredOn()->getTimestamp()
            ),
            $resetRuntimeProperties
        );
    }
}