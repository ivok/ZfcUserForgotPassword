<?php

namespace ZfcUserForgotPassword;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'service_manager' => [
        'factories' => [
            Service\ForgotPassword::class => Service\ForgotPasswordFactory::class,
            Model\ForgotPassword::class => Model\ForgotPasswordFactory::class,
            Form\ForgotPassword::class => Form\ForgotPasswordFactory::class,
            Model\ResetPassword::class => Model\ResetPasswordFactory::class,
            Form\ResetPassword::class => Form\ResetPasswordFactory::class,
            /**
             * You want to override this.
             */
            'zfcuserforgotpassword_sender' => Sender\BlackholeFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ForgotPassword::class => Controller\ForgotPasswordFactory::class,
            Controller\ResetPassword::class => Controller\ResetPasswordFactory::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ]
            ],
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Entity',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'zfcuser' => [
                'child_routes' => [
                    'forgot_password' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/forgot-password',
                            'defaults' => [
                                'controller' => Controller\ForgotPassword::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'success' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/success',
                                    'defaults' => [
                                        'action' => 'success',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'reset_password' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/reset-password',
                            'defaults' => [
                                'controller' => Controller\ResetPassword::class,
                                'action' => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'perform' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:user/:nonce',
                                ],
                            ],
                            'success' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/success',
                                    'defaults' => [
                                        'action' => 'success',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
