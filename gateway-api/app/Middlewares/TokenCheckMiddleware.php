<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 22:03
 */

namespace App\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class TokenCheckMiddleware
 * @package App\Middlewares
 * @Bean()
 */
class TokenCheckMiddleware implements MiddlewareInterface
{
    /**
     * Redis 服务的Rpc client
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCache;
    /**
     * token 权限校验
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        $headerToken = null;
//        if($request->getHeader('token'))
//        {
//            $headerToken = $request->getHeaderLine('token');
//        }
//        $requestToken = $request->input('token');
//        if(!$headerToken && !$requestToken)
//            return response()->json(['code' => 4,'data' => [],'msg' => '缺少token']);
//
//        if($headerToken || $requestToken)
//        {
//            $token = $headerToken?$headerToken:$requestToken;
//            $user = $this->userCache->getUserByToken($token);
//            if(!$user)
//                 return response()->json(['code' => 4,'data' => [],'msg' => 'token非法']);
//        }
        $response = $handler->handle($request);;
        return $response->withAddedHeader('Middleware-Token-Verify', 'success');
    }
}