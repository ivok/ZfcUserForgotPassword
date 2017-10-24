<?php

return [
    'doctrine' => [
        'cache' => [
            'array' => [
                'class' => Doctrine\Common\Cache\ArrayCache::class,
                'namespace' => 'DoctrineModule',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'eventmanager' => 'orm_default',
                'params' => [
                    'user' => 'travis',
                    'password' => '',
                    'url' => 'pdo-mysql://travis@localhost/zfcuserforgotpassword?charset=utf8',
                ],
                'driverClass' => 'PDOMySqlDriver',
            ],
        ],
        'configuration' => [
            'orm_default' => [],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'ZfcUserForgotPassword\\Entity' => 'ZfcUserForgotPassword_driver',
                    'ZfcUser\\Entity' => 'ZfcUser_driver',
                ],
            ],
            'ZfcUserForgotPassword_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'paths' => __DIR__ . '/../src/Entity',
            ],
            'ZfcUser_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'paths' => __DIR__ . '/../vendor/zf-commons/zfc-user-doctrine-orm/config/xml/zfcuser',
            ],
        ],
        'entitymanager' => [
            'orm_default' => [
                'connection' => 'orm_default',
                'configuration' => 'orm_default',
            ],
        ],
        'eventmanager' => [
            'orm_default' => [],
        ],
        'entity_resolver' => [
            'orm_default' => [],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'DoctrineModule' => \DoctrineModule\ServiceFactory\AbstractDoctrineServiceFactory::class,
        ],
        'aliases' => [
            'config' => 'Configuration',
            'Config' => 'Configuration',
        ],
    ],
    'doctrine_factories' => [
        'connection' => \DoctrineORMModule\Service\DBALConnectionFactory::class,
        'configuration' => \DoctrineORMModule\Service\ConfigurationFactory::class,
        'entitymanager' => \DoctrineORMModule\Service\EntityManagerFactory::class,
        'entity_resolver' => \DoctrineORMModule\Service\EntityResolverFactory::class,
        'cache' => \DoctrineModule\Service\CacheFactory::class,
        'eventmanager' => \DoctrineModule\Service\EventManagerFactory::class,
        'driver' => \DoctrineModule\Service\DriverFactory::class,
    ],
];
