<?php

return [
    'doctrine' => [
        'cache' => [
            'apc' => [
                'class' => \Doctrine\Common\Cache\ApcCache::class,
                'namespace' => 'DoctrineModule',
            ],
            'apcu' => [
                'class' => Doctrine\Common\Cache\ApcuCache::class,
                'namespace' => 'DoctrineModule',
            ],
            'array' => [
                'class' => Doctrine\Common\Cache\ArrayCache::class,
                'namespace' => 'DoctrineModule',
            ],
            'filesystem' => [
                'class' => Doctrine\Common\Cache\FilesystemCache::class,
                'directory' => 'data/DoctrineModule/cache',
                'namespace' => 'DoctrineModule',
            ],
            'memcache' => [
                'class' => Doctrine\Common\Cache\MemcacheCache::class,
                'instance' => 'my_memcache_alias',
                'namespace' => 'DoctrineModule',
            ],
            'memcached' => [
                'class' => Doctrine\Common\Cache\MemcachedCache::class,
                'instance' => 'my_memcached_alias',
                'namespace' => 'DoctrineModule',
            ],
            'predis' => [
                'class' => Doctrine\Common\Cache\PredisCache::class,
                'instance' => 'my_predis_alias',
                'namespace' => 'DoctrineModule',
            ],
            'redis' => [
                'class' => Doctrine\Common\Cache\RedisCache::class,
                'instance' => 'my_redis_alias',
                'namespace' => 'DoctrineModule',
            ],
            'wincache' => [
                'class' => Doctrine\Common\Cache\WinCacheCache::class,
                'namespace' => 'DoctrineModule',
            ],
            'xcache' => [
                'class' => Doctrine\Common\Cache\XcacheCache::class,
                'namespace' => 'DoctrineModule',
            ],
            'zenddata' => [
                'class' => Doctrine\Common\Cache\ZendDataCache::class,
                'namespace' => 'DoctrineModule',
            ],
        ],
        'authenticationadapter' => [
            'odm_default' => true,
            'orm_default' => true,
        ],
        'authenticationstorage' => [
            'odm_default' => true,
            'orm_default' => true,
        ],
        'authenticationservice' => [
            'odm_default' => true,
            'orm_default' => true,
        ],
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'eventmanager' => 'orm_default',
                'params' => [
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'travis',
                    'password' => '',
                    'url' => 'pdo-mysql://travis@localhost/zfcuserforgotpassword?charset=utf8',
                ],
                'driverClass' => 'PDOMySqlDriver',
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'hydration_cache' => 'array',
                'driver' => 'orm_default',
                'generate_proxies' => true,
                'proxy_dir' => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace' => 'DoctrineORMModule\\Proxy',
                'filters' => [],
                'datetime_functions' => [],
                'string_functions' => [],
                'numeric_functions' => [],
                'second_level_cache' => [],
                'filter_schema_assets_expression' => '',
            ],
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
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Entity',
            ],
            'ZfcUser_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'cache' => 'array',
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
            'orm_default' => [
                'subscribers' => [],
            ],
        ],
        'sql_logger_collector' => [
            'orm_default' => [],
        ],
        'mapping_collector' => [
            'orm_default' => [],
        ],
        'formannotationbuilder' => [
            'orm_default' => [],
        ],
        'entity_resolver' => [
            'orm_default' => [],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Doctrine\ORM\EntityManager' => \DoctrineORMModule\Service\EntityManagerAliasCompatFactory::class,
        ],
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
        'sql_logger_collector' => \DoctrineORMModule\Service\SQLLoggerCollectorFactory::class,
        'mapping_collector' => \DoctrineORMModule\Service\MappingCollectorFactory::class,
        'formannotationbuilder' => \DoctrineORMModule\Service\FormAnnotationBuilderFactory::class,
        'migrations_configuration' => \DoctrineORMModule\Service\MigrationsConfigurationFactory::class,
        'migrations_cmd' => \DoctrineORMModule\Service\MigrationsCommandFactory::class,
        'cache' => \DoctrineModule\Service\CacheFactory::class,
        'eventmanager' => \DoctrineModule\Service\EventManagerFactory::class,
        'driver' => \DoctrineModule\Service\DriverFactory::class,
        'authenticationadapter' => \DoctrineModule\Service\Authentication\AdapterFactory::class,
        'authenticationstorage' => \DoctrineModule\Service\Authentication\StorageFactory::class,
        'authenticationservice' => \DoctrineModule\Service\Authentication\AuthenticationServiceFactory::class,
    ],
];
