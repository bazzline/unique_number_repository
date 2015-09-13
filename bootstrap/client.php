<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-09-12
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Net\Bazzline\Component\Cli\Arguments\Arguments;
use Net\Bazzline\Component\CommandCollection\Http\Curl;

//begin of dependencies
$arguments  = new Arguments($argv);
$baseUrl    = '/unique-number-repository';
$command    = new Curl();
$filePath   = __DIR__ . '/../configuration/client.local.php';
$token      = '';
$values     = $arguments->getValues();

if (file_exists($filePath)) {
    require_once $filePath;
}
//end of dependencies

//begin of configuration
$command->addHeader('Authorization: ' . $token);
$command->isJson();
$command->beSilent();
$command->noSslSecurity();
//end of configuration

/**
 * @param callable $callable
 * @param string $usage
 */
function execute($callable, $usage) {
    try {
        call_user_func($callable);
    } catch (Exception $exception) {
        echo 'An error occurred' . PHP_EOL;
        echo $exception->getMessage() . PHP_EOL;
        echo PHP_EOL;
        echo $usage;
        exit(1);
    }
}

/**
 * @param array $array
 * @return array
 */
function extractValues(array $array)
{
    return array_values($array);
}

/**
 * @param array $values
 * @param int $expectedNumberOfValues
 */
function throwExceptionIfInvalidNumberOfValuesWasProvided($values, $expectedNumberOfValues)
{
    $numberOfValues                 = count($values);
    $invalidNumberOfValuesProvided  = ($numberOfValues !== $expectedNumberOfValues);

    if ($invalidNumberOfValuesProvided) {
        $message = 'invalid number of arguments supplied';

        throw new RuntimeException($message);
    }
}

/**
 * @param string $value
 * @param string $name
 */
function throwExceptionIfValueIsInvalid($value, $name)
{
    $invalidValueProvided = (strlen($value) < 1);

    if ($invalidValueProvided) {
        $message = 'invalid ' . $name . ' supplied';

        throw new RuntimeException($message);
    }
}
