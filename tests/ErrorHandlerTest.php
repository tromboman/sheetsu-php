<?php
/**
 * Unit test for Error Handler class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;

use Sheetsu\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider exceptionProvider
     * @param $exception
     */
    public function testConstructSetsExceptionAndExceptionMessageInArrayAttributes($exception)
    {
        $errorHandler = new ErrorHandler($exception);
        $this->assertTrue($errorHandler->getFirstException() instanceof \ErrorException);
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testCreateCreatesErrorHandlerObject($exceptions)
    {
        $errorHandler = ErrorHandler::create($exceptions);
        $this->assertTrue($errorHandler->getFirstException() instanceof \ErrorException);
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetErrorsReturnsArrayOfErrors($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $errors = $errorHandler->getErrors();
        $this->assertTrue(is_array($errors));
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetFirstErrorReturnsFirstError($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $firstError = $errorHandler->getFirstError();
        $this->assertEquals('This is a message', $firstError);
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetExceptionsReturnsArrayOfExceptions($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $exceptions = $errorHandler->getExceptions();
        $this->assertTrue(is_array(($exceptions)));
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetFirstExceptionReturnsFirstException($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $exception = $errorHandler->getFirstException();
        $this->assertTrue($exception instanceof \ErrorException);
    }


    /**
     * @dataProvider curlProvider
     * @param $curl
     */
    public function testCheckForErrorsInCurlChecksHttpStatusCodeOkInGivenCurlObject($curl)
    {
        $result = ErrorHandler::checkForErrorsInCurl($curl);
        $exception = $result->getFirstException();
        $this->assertTrue($exception instanceof \ErrorException);
    }


    public function exceptionProvider()
    {
        $exception = new \ErrorException('This is a message');
        return [
            [
                $exception
            ]
        ];
    }

    public function arExceptionsProvider()
    {
        $exception = new \ErrorException('This is a message');
        return [
            [
                $exception,
                $exception,
                $exception
            ],
            [[$exception, $exception]]
        ];
    }

    public function curlProvider()
    {
        $fourhundred = $this->createResponseObject(400, '{"glossary": {"title": "example glossary","resume": "something"},"error": "This is a message"}');
        $fourhundredone = $this->createResponseObject(401, '{"glossary": {"title": "example glossary","resume": "something"},"error": "This is a message"}');
        $fivehundred = $this->createResponseObject(500, '{"glossary": {"title": "example glossary","resume": "something"},"error": "This is a message"}');
        $fivehundredempty = $this->createResponseObject(401, '');
        return [
            'error 400'                    => [$fourhundred],
            'error 401'                    => [$fourhundredone],
            'error 500'                    => [$fivehundred],
            'error 500 con response vacío' => [$fivehundredempty]
        ];
    }

    private function createResponseObject($httpStatusCode, $response)
    {
        $object = new \stdClass();
        $object->http_status_code = $httpStatusCode;
        $object->response = $response;
        return $object;
    }
}
