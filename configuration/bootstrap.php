<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-07
 */

require __DIR__ . '/../vendor/autoload.php';

use Net\Bazzline\UniqueNumberRepository\Application\Service\ApplicationLocator;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\RepositoryRequest;
use Net\Bazzline\UniqueNumberRepository\Domain\Model\UniqueNumberRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

const VERSION = '1.0.0';

$application    = new Application();
$locator        = new ApplicationLocator();

//begin of overriding default functionality
$application->error(function (Exception $exception, $code) use ($application) {
    switch ($code) {
        case 404:
            $message = 'not found';
            break;
        case 400:
            $message = $exception->getMessage();
            break;
        default:
            $message = 'the server made a boh boh:' . PHP_EOL .
                $exception->getMessage() . PHP_EOL .
                PHP_EOL .
                $exception->getTraceAsString();
            break;
    }

    return new Response($message, $code);
});
//end of overriding default functionality

//begin of routing
$application->delete('/unique-number-repository/{name}', function(Request $request) use ($application, $locator) {
    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already

    $repository = $locator->getRepositoryStorage();
    $repository->filterBy('name', urldecode($request->get('name')));
    $result = $repository->delete();

    if ($result !== true) {
        $application->abort(404);
    }

    return $application->json('ok');
});
$application->get('/unique-number-repository', function() use ($application, $locator) {
    $repository = $locator->getRepositoryStorage();
    $collection = $repository->readMany();
    $content    = array();

    foreach ($collection as $data) {
        $content[] = array(
            'name' => $data['name'],
        );
    }

    return $application->json($content);
});
$application->post('/unique-number-repository', function(Request $request) use ($application, $locator) {
    $name   = urldecode($request->get('name', null));
    $user   = urldecode($request->get('applicant_name', null));

    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already
    $repositoryRequest  = new RepositoryRequest($user, $name, new DateTime());

    $repository = $locator->getRepositoryStorage();
    $id         = $repository->create(
        array(
            'applicant_name'    => $repositoryRequest->applicantName(),
            'name'              => $repositoryRequest->name(),
            'occurred_on'       => $repositoryRequest->occurredOn()->getTimestamp()
        )
    );

    return $application->json(array('id' => $id));
});

$application->post('/unique-number-repository/{name}', function(Request $request) use ($application, $locator) {
    $name   = urldecode($request->attributes->get('name'));
    $user   = urldecode($request->get('applicant_name'));

    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already
    $numberEnumerator       = $locator->getUniqueNumberEnumerator();
    $repository             = $locator->getUniqueNumberStorage();
    $uniqueNumberRequest    = new UniqueNumberRequest($user, $numberEnumerator->increment($name), new DateTime(), $name);


    $repository->create(
        array(
            'applicant_name'    => $uniqueNumberRequest->applicantName(),
            'number'            => $uniqueNumberRequest->number(),
            'occurred_on'       => $uniqueNumberRequest->occurredOn()->getTimestamp(),
            'repository_name'   => $uniqueNumberRequest->repositoryName()
        )
    );

    return $application->json(array('number' => $uniqueNumberRequest->number()));
});
$application->delete('/unique-number-repository/{name}/{number}', function(Request $request) use ($application, $locator) {
    //@todo data validation (repository_name, applicant_name)
    //@todo check if repository name does not exist already
    $name   = urldecode($request->attributes->get('name', null));
    $number = $request->attributes->get('number', null);

    $repository = $locator->getUniqueNumberStorage();
    $repository->filterBy('repository_name', $name);
    $repository->filterBy('number', (int) $number);
    $result = $repository->delete();

    if ($result !== true) {
        $application->abort(404);
    }

    return $application->json('ok');
});
$application->get('/unique-number-repository/{name}', function(Request $request) use ($application, $locator)  {
    $name       = urldecode($request->get('name', null));
    $repository = $locator->getUniqueNumberStorage();

    $repository->filterBy('repository_name', $name);
    $collection = $repository->readMany();
    $content    = array();

    foreach ($collection as $data) {
        $content[] = array(
            'number'    => $data['number']
        );
    }

    return $application->json($content);
});

$application->get('/version', function() use ($application) {
    return $application->json(VERSION);
});
//end of routing

$application->run();
