<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/11/26
 * Time: 20:52
 */

namespace App\Controllers\Api;
use App\Services\AuthManagerService;
use Swoft\App;
use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class AuthorizationsResuource
 * @Controller(prefix="/oauth")
 * @package App\Controllers\Api
 */
class AuthorizationsResuource
{
    /**
     * @RequestMapping(route="token",method={RequestMethod::POST})
     */
    public function oauth(Request $request):array
    {
        $identity = $request->getAttribute(AuthConstants::BASIC_USER_NAME)??'';
        $credential = $request->getAttribute(AuthConstants::BASIC_PASSWORD)??'';
        if(!$identity || !$credential)
        {
            return ['code' => 400,'message' => 'identity and credential are required'];
        }
        /**
         * @var AuthManagerService $manager
         */
        $manager = App::getBean(AuthManagerInterface::class);
        /** @var AuthSession $session*/
        $session = $manager->adminBasicLogin($identity,$credential);
        $data = [
            'token' => $session->getToken(),
            'expire' => $session->getExpirationTime()
        ];
        return $data;
    }

}