<?php
/**
 * admin-robots-txt config file
 * @package admin-robots-txt
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-robots-txt',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-robots-txt',
    '__files' => [
        'modules/admin-robots-txt' => [ 'install', 'remove', 'update' ],
        'theme/admin/setting/robots' => [ 'install', 'remove', 'update' ],
        'robots.txt' => [ 'remove' ],
        'robots.txt-trash' => [ 'remove' ]
    ],
    '__dependencies' => [
        'admin'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'AdminRobotsTxt\\Controller\\RobotController' => 'modules/admin-robots-txt/controller/RobotController.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'admin' => [
            'adminSettingRobots' => [
                'rule' => '/setting/robots',
                'handler' => 'AdminRobotsTxt\\Controller\\Robot::edit'
            ],
            'adminSettingRobotsEnable' => [
                'rule' => '/setting/robots/enabled',
                'handler' => 'AdminRobotsTxt\\Controller\\Robot::enable'
            ],
            'adminSettingRobotsDisabled' => [
                'rule' => '/setting/robots/disabled',
                'handler' => 'AdminRobotsTxt\\Controller\\Robot::disabled'
            ],
            'adminSettingRobotsDisable' => [
                'rule' => '/setting/robots/disable',
                'handler' => 'AdminRobotsTxt\\Controller\\Robot::disable'
            ]
        ]
    ],
    
    'admin' => [
        'menu' => [
            'setting' => [
                'label'     => 'Setting',
                'submenu'   => [
                    'robots-txt' => [
                        'label'     => 'Robots.txt',
                        'perms'     => 'manage_robots_txt',
                        'target'    => 'adminSettingRobots',
                        'order'     => 300
                    ]
                ]
            ]
        ]
    ],
    
    'form' => [
        'admin-robot-file' => [
            'text'  => [
                'type'  => 'textarea',
                'label' => 'New Content',
                'attrs' => [
                    'spellcheck' => 'false'
                ],
                'rules' => [
                    'required' => true
                ]
            ]
        ]
    ]
];