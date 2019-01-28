<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception;

use App\Exception\Http\HttpExceptionHandler;
use Composer\XdebugHandler\Status;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;
use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Bean\Annotation\PointExecution;
use Swoft\Exception\RuntimeException;
use Exception;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Exception\BadMethodCallException;
use Swoft\Exception\ValidatorException;
use Swoft\Http\Server\Exception\BadRequestException;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Exception\RpcResponseException;
use Swoft\Rpc\Exception\RpcStatusException;

/**
 * the handler of global exception
 *
 * @ExceptionHandler()
 * @uses      Handler
 * @version   2018年01月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SwoftExceptionHandler
{
    /**
     * @Handler(Exception::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerException(Response $response, \Throwable $throwable)
    {
        $file      = $throwable->getFile();
        $line      = $throwable->getLine();
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();
        $data = [];
        $statusCode = 10000;
        if(property_exists($throwable,'data'))
            $data = $throwable->data;
        if(property_exists($throwable,'statusCode'))
            $statusCode = $throwable->statusCode;

        $data = ['code' => $code,'msg' => $exception,'data' => $data,'statusCode' => $statusCode, 'file' => $file, 'line' => $line];
        App::error(json_encode($data));
        return $response->json($data);
    }

    /**
     * 捕获Rpc 返回状态失败(未设置服务降级 调试时使用的异常抓捕)
     * @Handler(RpcStatusException::class)
     * @param Response $response
     * @param \Throwable $throwable
     */
    public function handlerRpcStatusException(Response $response,\Throwable $throwable)
    {
        $msg  = $throwable->getResponseMessage();
        $status = $throwable->getStatus();
        $data = $throwable->getData();
        $returnData = ['code' => StatusEnum::Fail,'msg' => $msg,'data' => $data,'statusCode' => $status];
        return $response->json($returnData);
    }
    /**
     * 捕获Rpc Response解析失败(未设置服务降级)
     * @Handler(RpcResponseException::class)
     * @param Response $response
     * @param \Throwable $throwable
     */
    public function handlerRpcResponseException(Response $response,\Throwable $throwable)
    {
        $data  = $throwable->getResponse();
        $returnData = ['code' => StatusEnum::Fail,'msg' => '','data' => $data,'statusCode' => 10000];
        return $response->json($returnData);
    }

    /**
     * 捕获Rpc Client 请求失败(未设置服务降级)
     * @Handler(RpcClientException::class)
     * @param Response $response
     * @param \Throwable $throwable
     */
    public function handlerRpcClientException(Response $response,\Throwable $throwable)
    {
        $msg  = $throwable->getMessage();
        $returnData = ['code' => StatusEnum::Fail,'msg' => $msg,'data' => '服务未开机','statusCode' => 10000];
        return $response->json($returnData);
    }
    /**
     * @Handler(RuntimeException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerRuntimeException(Response $response, \Throwable $throwable)
    {
        $file      = $throwable->getFile();
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        return $response->json([$exception, 'runtimeException']);
    }

    /**
     * @Handler(ValidatorException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerValidatorException(Response $response, \Throwable $throwable)
    {
        $exception = $throwable->getMessage();

        return $response->json(['code' => 4,'data' => [],'msg' => $exception]);
    }



    /**
     * @Handler(BadRequestException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerBadRequestException(Response $response, \Throwable $throwable)
    {
        $exception = $throwable->getMessage();

        return $response->json(['message' => $exception]);
    }

    /**
     * @Handler(BadMethodCallException::class)
     *
     * @param Request    $request
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerViewException(Request $request, Response $response, \Throwable $throwable)
    {
        $name  = $throwable->getMessage(). $request->getUri()->getPath();
        $notes = [
            'New Generation of PHP Framework',
            'High Performance, Coroutine and Full Stack',
        ];
        $links = [
            [
                'name' => 'Home',
                'link' => 'http://www.swoft.org',
            ],
            [
                'name' => 'Documentation',
                'link' => 'http://doc.swoft.org',
            ],
            [
                'name' => 'Case',
                'link' => 'http://swoft.org/case',
            ],
            [
                'name' => 'Issue',
                'link' => 'https://github.com/swoft-cloud/swoft/issues',
            ],
            [
                'name' => 'GitHub',
                'link' => 'https://github.com/swoft-cloud/swoft',
            ],
        ];
        $data  = compact('name', 'notes', 'links');

        return view('exception/index', $data);
    }

}