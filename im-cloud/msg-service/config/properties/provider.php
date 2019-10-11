<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'consul' => [
        'address' => 'http://localhost',
        'port'    => 8500,
        'register' => [
            'id'                => '',
            'name'              => '',
            'tags'              => [],
            'enableTagOverride' => false,
            'service'           => [
                'address' => 'localhost',
                'port'   => '8093',
            ],
            'check'             => [
                'id'       => '',
                'name'     => '',
                'tcp'      => 'localhost:8093',
                'interval' => 10,
                'timeout'  => 1,
            ],
        ],
        'discovery' => [
            'name' => 'msg_service',
            'dc' => 'dc',
            'near' => '',
            'tag' =>'',
            'passing' => true
        ]
    ],
];