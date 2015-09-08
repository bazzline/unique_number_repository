<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */

require __DIR__ . '/../vendor/autoload.php';

use flight\Engine;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\RepositoryRequest;
use Net\Bazzline\UniqueNumberRepository\Application\Service\ApplicationLocator;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;
const VERSION = '1.0.0';

$application    = new Engine();
$locator        = new ApplicationLocator();

//begin of overriding default functionality
$application->map('notFound', function() use ($application) {
    $application->_json('not found', 404);
});
//end of overriding default functionality

//begin of routing
$application->_route('DELETE /unique-number-repository/@name', function($name) use ($application, $locator) {
    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already

    $repository = $locator->getRepositoryRepository();
    $repository->filterBy('name', urldecode($name));
    $result = $repository->delete();

    if ($result !== true) {
        $application->_stop(404);
    }
});
$application->_route('GET /unique-number-repository', function() use ($application, $locator) {
    $repository = $locator->getRepositoryRepository();
    $collection = $repository->readMany();
    $content    = array();

    foreach ($collection as $data) {
        $content[] = array(
            'name'              => $data['name'],
        );
    }
    $application->_json($content);
});
$application->_route('POST /unique-number-repository', function() use ($application, $locator) {
    /** @var \flight\net\Request $request */
    $request    = $application->request();
    $data       = $request->data;

    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already
    $repositoryRequest  = new RepositoryRequest($data['applicant_name'], $data['name'], new DateTime());

    $repository = $locator->getRepositoryRepository();
    $id         = $repository->create(
        array(
            'applicant_name'    => $repositoryRequest->applicantName(),
            'name'              => $repositoryRequest->name(),
            'occurred_on'       => $repositoryRequest->occurredOn()->getTimestamp()
        )
    );
    $application->_json(array('id' => $id));
});

$application->_route('POST /unique-number-repository/@name', function($name) use ($application, $locator) {
    /** @var \flight\net\Request $request */
    $request    = $application->request();
    $data       = $request->data;

    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already
    $numberEnumerator       = $locator->getUniqueNumberEnumerator();
    $repository             = $locator->getUniqueNumberRepository();
    $uniqueNumberRequest    = new UniqueNumberRequest($data['applicant_name'], $numberEnumerator->increment($name), new DateTime(), $name);


    $repository->create(
        array(
            'applicant_name'    => $uniqueNumberRequest->applicantName(),
            'number'            => $uniqueNumberRequest->number(),
            'occurred_on'       => $uniqueNumberRequest->occurredOn()->getTimestamp(),
            'repository_name'   => $uniqueNumberRequest->repositoryName()
        )
    );

    $application->_json(array('number' => $uniqueNumberRequest->number()));
});
$application->_route('DELETE /unique-number-repository/@name/@number', function($name, $number) use ($application, $locator) {
    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already

    $repository = $locator->getUniqueNumberRepository();
    $repository->filterBy('repository_name', urldecode($name));
    $repository->filterBy('number', $number);
    $result = $repository->delete();

    if ($result !== true) {
        $application->_stop(404);
    }
});
$application->_route('GET /unique-number-repository/@name', function($name) use ($application, $locator)  {
    $repository = $locator->getUniqueNumberRepository();
    $repository->filterBy('repository_name', $name);
    $collection = $repository->readMany();
    $content    = array();

    foreach ($collection as $data) {
        $content[] = array(
            'number'    => $data['number']
        );
    }
    $application->_json($content);
});

$application->_route('GET /version', function() use ($application) {
    $application->_json(VERSION);
});
//end of routing

$application->_start();
