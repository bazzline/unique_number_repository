<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-12
 */
namespace Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage;

use DateTime;
use Net\Bazzline\Component\Database\FileStorage\Storage\Storage;

/**
 * Class AbstractStorage
 * @package Net\Bazzline\UniqueNumberRepository\Infrastructure\Storage
 */
abstract class AbstractStorage extends Storage
{
    /**
     * @param $timestamp
     * @return DateTime
     */
    protected function createDateTimeFromTimestamp($timestamp)
    {
        return new DateTime(date('Y-m-d H:i:s', $timestamp));
    }
}