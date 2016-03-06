<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */
namespace Net\Bazzline\UniqueNumberRepository\Domain\Model;

use Datetime;

class RepositoryRequest
{
    /** @var string */
    private $applicantName;

    /** @var string */
    private $name;

    /** @var DateTime */
    private $occurredOn;

    /**
     * @param string $applicantName
     * @param string $name
     * @param Datetime $occurredOn
     */
    public function __construct($applicantName, $name, Datetime $occurredOn)
    {
        $this->applicantName    = $applicantName;
        $this->name             = $name;
        $this->occurredOn       = $occurredOn;
    }

    /**
     * @return int
     */
    public function applicantName()
    {
        return $this->applicantName;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
