<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-11
 */
namespace Net\Bazzline\UniqueNumberRepository\Application;

use DateTime;
use Exception;
use flight\Engine;
use Net\Bazzline\UniqueNumberRepository\Application\Service\ApplicationLocator;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\RepositoryRequest;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;

/**
 * Class Application
 * @package Net\Bazzline\UniqueNumberRepository\Application
 */
class Application
{
    /** @var Engine */
    private $engine;

    /** @var ApplicationLocator */
    private $locator;

    /**
     * @param Engine $engine
     * @param ApplicationLocator $locator
     */
    public function __construct(Engine $engine, ApplicationLocator $locator)
    {
        $this->engine   = $engine;
        $this->locator  = $locator;
    }

    /**
     * @param Engine $engine
     * @param ApplicationLocator $locator
     * @return Application
     */
    public static function create(Engine $engine, ApplicationLocator $locator)
    {
        return new self($engine, $locator);
    }

    public function andRun()
    {
        $engine     = $this->engine;
        $locator    = $this->locator;

        //begin of overriding default functionality
        $engine->map('notFound', function() use ($engine) {
            $engine->_json('not found', 404);
        });
        $engine->map('error', function(Exception $exception) use ($engine) {
            $engine->_json($exception->getMessage(), 500);
        });
        //end of overriding default functionality

        //begin of routing
        $engine->_route('DELETE /unique-number-repository/@name', function($name) use ($engine, $locator) {
            //@todo data validation (repository_name, applicant_name)
            //@todo check if repository name does not exist already

            $repository = $locator->getRepositoryStorage();
            $repository->filterBy('name', urldecode($name));
            $result = $repository->delete();

            if ($result !== true) {
                $engine->_stop(404);
            }
        });
        $engine->_route('GET /unique-number-repository', function() use ($engine, $locator) {
            $repository = $locator->getRepositoryStorage();
            $collection = $repository->readMany();
            $content    = array();

            foreach ($collection as $data) {
                $content[] = array(
                    'name'              => $data['name'],
                );
            }
            $engine->_json($content);
        });
        $engine->_route('POST /unique-number-repository', function() use ($engine, $locator) {
            /** @var \flight\net\Request $request */
            $request    = $engine->request();
            $data       = $request->data;

            //@todo data validation (repository_name, applicant_name)
            //@todo check if repository name does not exist already
            $repositoryRequest  = new RepositoryRequest($data['applicant_name'], $data['name'], new DateTime());

            $repository = $locator->getRepositoryStorage();
            $id         = $repository->create(
                array(
                    'applicant_name'    => $repositoryRequest->applicantName(),
                    'name'              => $repositoryRequest->name(),
                    'occurred_on'       => $repositoryRequest->occurredOn()->getTimestamp()
                )
            );

            $engine->_json(array('id' => $id));
        });

        $engine->_route('POST /unique-number-repository/@name', function($name) use ($engine, $locator) {
            /** @var \flight\net\Request $request */
            $request    = $engine->request();
            $data       = $request->data;

            //@todo data validation (repository_name, applicant_name)
            //@todo check if repository name does not exist already
            $numberEnumerator       = $locator->getUniqueNumberEnumerator();
            $repository             = $locator->getUniqueNumberStorage();
            $uniqueNumberRequest    = new UniqueNumberRequest($data['applicant_name'], $numberEnumerator->increment($name), new DateTime(), $name);


            $repository->create(
                array(
                    'applicant_name'    => $uniqueNumberRequest->applicantName(),
                    'number'            => $uniqueNumberRequest->number(),
                    'occurred_on'       => $uniqueNumberRequest->occurredOn()->getTimestamp(),
                    'repository_name'   => $uniqueNumberRequest->repositoryName()
                )
            );

            $engine->_json(array('number' => $uniqueNumberRequest->number()));
        });
        $engine->_route('DELETE /unique-number-repository/@name/@number', function($name, $number) use ($engine, $locator) {
            //@todo data validation (repository_name, applicant_name)
            //@todo check if repository name does not exist already

            $repository = $locator->getUniqueNumberStorage();
            $repository->filterBy('repository_name', urldecode($name));
            $repository->filterBy('number', (int) $number);
            $result = $repository->delete();

            if ($result !== true) {
                $engine->_stop(404);
            }
        });
        $engine->_route('GET /unique-number-repository/@name', function($name) use ($engine, $locator)  {
            $repository = $locator->getUniqueNumberStorage();
            $repository->filterBy('repository_name', $name);
            $collection = $repository->readMany();
            $content    = array();

            foreach ($collection as $data) {
                $content[] = array(
                    'number'    => $data['number']
                );
            }
            $engine->_json($content);
        });

        $engine->_route('GET /version', function() use ($engine) {
            $engine->_json(VERSION);
        });
        //end of routing

        $engine->_start();
    }
}