<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */
namespace Net\Bazzline\UniqueNumberRepository\Domain\Model;

use DateTime;

class UniqueNumberRequest
{
    /** @var string */
    private $applicantName;

    /** @var  null|int */
    private $number;

    /** @var DateTime */
    private $occurredOn;

    /** @var string */
    private $repositoryName;

    /**
     * @param string $applicantName
     * @param int $number
     * @param DateTime $occurredOn
     * @param string $repositoryName
     */
    public function __construct($applicantName, $number, DateTime $occurredOn, $repositoryName)
    {
        $this->applicantName    = $applicantName;
        $this->number           = $number;
        $this->occurredOn       = $occurredOn;
        $this->repositoryName   = $repositoryName;
    }

    /**
     * @return string
     */
    public function applicantName()
    {
        return $this->applicantName;
    }

    /**
     * @return null|int
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * @return Datetime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }

    /**
     * @return string
     */
    public function repositoryName()
    {
        return $this->repositoryName;
    }
}
