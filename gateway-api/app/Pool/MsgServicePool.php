<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Pool;

use App\Pool\Config\GroupPoolConfig;
use App\Pool\Config\MsgPoolConfig;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use App\Pool\Config\UserPoolConfig;
use Swoft\Rpc\Client\Pool\ServicePool;

/**
 * the pool of user-service service
 *
 * @Pool(name="msgService")
 */
class MsgServicePool extends ServicePool
{
    /**
     * @Inject()
     *
     * @var MsgPoolConfig
     */
    protected $poolConfig;
}