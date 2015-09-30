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

require_once __DIR__ . '/../configuration/server.local.php';

//begin of dependencies
$application    = new Application();
$locator        = new ApplicationLocator();
//end of dependencies

//begin of overriding default functionality
$application->before(function (Request $request) use ($application, $token) {
    //begin of only allow requests with valid authorization token
    $isNotAuthorized = ($request->headers->get('authorization') !== $token);

    if ($isNotAuthorized) {
        $application->abort(401);
    }
    //end of only allow requests with valid authorization token

    //begin of decoding json request data
    $isJsonRequest = (0 === strpos($request->headers->get('Content-Type'), 'application/json'));

    if ($isJsonRequest) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    //end of decoding json request data
});

$application->error(function (Exception $exception, $code) use ($application) {
    switch ($code) {
        case 404:
            $message = 'not found';
            break;
        case 403:
            $message = 'forbidden';
            break;
        case 401:
            $message = 'unauthorized';
            break;
        case 400:
            $message = $exception->getMessage();
            break;
        default:
            $message = 'the server made a boh boh:' . PHP_EOL .
                $exception->getMessage() . PHP_EOL;
            break;
    }

    return new Response($message, $code);
});
//end of overriding default functionality

//begin of routing
$application->get('/version', function (Application $application) {
    //begin of process
    return $application->json(VERSION);
    //end of process
});

$application->delete('/unique-number-repository/{name}', function (Application $application, Request $request) use ($locator) {
    //begin of runtime parameters
    $name   = urldecode($request->get('name'));
    $user   = urldecode($request->get('applicant_name'));
    //end of runtime parameters

    //begin of dependencies
    $repositoryStorage      = $locator->getRepositoryStorage();
    $uniqueNumberStorage    = $locator->getUniqueNumberStorage();
    //end of dependencies

    //begin of validation
    $repositoryStorage->resetRuntimeProperties();
    $repositoryStorage->filterByApplicantName($user);
    $repositoryStorage->filterByName($name);
    $repositoryNameDoesNotExist = (!$repositoryStorage->has());

    if ($repositoryNameDoesNotExist) {
        $application->abort(404);
    }
    $repositoryStorage->resetRuntimeProperties();
    //end of validation

    //begin of process
    $repositoryStorage->filterByApplicantName($user);
    $repositoryStorage->filterByName($name);
    $result = $repositoryStorage->delete();

    if ($result === true) {
        $uniqueNumberStorage->resetRuntimeProperties();
        $uniqueNumberStorage->filterByRepositoryName($name);
        $result = $uniqueNumberStorage->delete();

        if ($result !== true) {
            $application->abort(503);
        }
    } else {
        $application->abort(503);
    }

    return $application->json('ok');
    //end of process
});
$application->get('/unique-number-repository', function (Application $application) use ($locator) {
    //begin of dependencies
    $storage    = $locator->getRepositoryStorage();
    //end of dependencies

    //begin of process
    $collection = $storage->readMany();
    $content    = array();

    foreach ($collection as $repositoryRequest) {
        $content[] = array(
            'name' => $repositoryRequest->name()
        );
    }

    return $application->json($content);
    //end of process
});
$application->post('/unique-number-repository', function (Application $application, Request $request) use ($locator) {
    //begin of runtime parameters
    $name   = urldecode($request->get('repository_name'));
    $user   = urldecode($request->get('applicant_name'));
    //end of runtime parameters

    //begin of dependencies
    $repositoryRequest  = new RepositoryRequest($user, $name, new DateTime());
    $storage            = $locator->getRepositoryStorage();
    //end of dependencies

    //begin of validation
    $storage->resetRuntimeProperties();
    $storage->filterByName($repositoryRequest->name());
    $repositoryNameExistsAlready = $storage->has();

    if ($repositoryNameExistsAlready) {
        $application->abort(400, 'repository name exists already');
    }
    $storage->resetRuntimeProperties();
    //end of validation

    //begin of process
    $createdId = $storage->createFrom($repositoryRequest);

    return $application->json(array('id' => $createdId));
    //end of process
});

$application->post('/unique-number-repository/{name}', function (Application $application, Request $request, $name) use ($locator) {
    //begin of runtime parameters
    $name   = urldecode($name);
    $user   = urldecode($request->get('applicant_name'));
    //end of runtime parameters

    //begin of dependencies
    $numberEnumerator       = $locator->getUniqueNumberEnumerator();
    $repositoryStorage      = $locator->getRepositoryStorage();
    $uniqueNumberStorage    = $locator->getUniqueNumberStorage();
    //end of dependencies

    //begin of validation
    $repositoryStorage->filterByName($name);
    $repositoryNameDoesNotExist = (!$repositoryStorage->has());

    if ($repositoryNameDoesNotExist) {
        $application->abort(400, 'repository name does not exist');
    }
    $repositoryStorage->resetRuntimeProperties();
    //end of validation

    //begin of process
    $uniqueNumberRequest = new UniqueNumberRequest($user, $numberEnumerator->increment($name), new DateTime(), $name);
    $uniqueNumberStorage->createFrom($uniqueNumberRequest);

    return $application->json(array('number' => $uniqueNumberRequest->number()));
    //end of process
});
$application->delete('/unique-number-repository/{name}/{number}', function (Application $application, Request $request, $name, $number) use ($locator) {
    //begin of runtime parameters
    $name   = urldecode($name);
    $number = (int) $number;
    $user   = urldecode($request->get('applicant_name'));
    //end of runtime parameters

    //begin of dependencies
    $storage = $locator->getUniqueNumberStorage();
    //end of dependencies

    //begin of validation
    $storage->filterByApplicantName($user);
    $storage->filterByNumber($number);
    $storage->filterByRepositoryName($name);
    $numberDoesNotExistInThisRepository = (!$storage->has());

    if ($numberDoesNotExistInThisRepository) {
        //@todo refactore - check if number exist and later on check if 
        //applicant name is the same. if the name differs, we have to send a 
        //403
        $application->abort(404, 'number does not exist in this repository');
    }
    $storage->resetRuntimeProperties();
    //end of validation

    //begin of process
    $storage->filterByApplicantName($user);
    $storage->filterByNumber($number);
    $storage->filterByRepositoryName($name);
    $result = $storage->delete();

    if ($result !== true) {
        $application->abort(503);
    }

    return $application->json('ok');
    //end of process
});
$application->get('/unique-number-repository/{name}', function (Application $application, $name) use ($locator)  {
    //begin of runtime parameters
    $name   = urldecode($name);
    //end of runtime parameters

    //begin of dependencies
    $numberStorage      = $locator->getUniqueNumberStorage();
    $repositoryStorage  = $locator->getRepositoryStorage();
    //end of dependencies

    //begin of validation
    $repositoryStorage->filterByName($name);
    $repositoryDoesNotExist = (!$repositoryStorage->has());

    if ($repositoryDoesNotExist) {
        $application->abort(404, 'repository does not exist');
    }
    $repositoryStorage->resetRuntimeProperties();
    //end of validation

    //begin of process
    $numberStorage->resetRuntimeProperties();
    $numberStorage->filterBy('repository_name', $name);
    $collection = $numberStorage->readMany();
    $content    = array();

    foreach ($collection as $uniqueNumberRequest) {
        $content[] = array(
            'number'    => $uniqueNumberRequest->number()
        );
    }

    return $application->json($content);
    //end of process
});
//end of routing

$application->run();
