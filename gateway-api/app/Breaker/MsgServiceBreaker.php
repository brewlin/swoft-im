<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Breaker;

use Swoft\Sg\Bean\Annotation\Breaker;
use Swoft\Bean\Annotation\Value;
use Swoft\Sg\Circuit\CircuitBreaker;

/**
 * the breaker of msg_service
 *
 * @Breaker("msgService")
 */
class MsgServiceBreaker extends CircuitBreaker
{
    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @Value(name="${config.breaker.msg_service.failCount}")
     * @var int
     */
    protected $switchToFailCount = 3;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @Value(name="${config.breaker.msg_service.successCount}")
     * @var int
     */
    protected $switchToSuccessCount = 3;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @Value(name="${config.breaker.msg_service.delayTime}")
     * @var int
     */
    protected $delaySwitchTimer = 500;
}