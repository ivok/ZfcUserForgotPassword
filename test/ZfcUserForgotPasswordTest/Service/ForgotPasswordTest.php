<?php

namespace ZfcUserForgotPasswordTest\Service;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;
use ZfcUser\Options\ModuleOptions as ZfcUserOptions;
use ZfcUser\Entity\User;
use ZfcUserForgotPassword\Entity\Reset;
use ZfcUserForgotPassword\Nonce\Generator;
use DoctrineORMModule\Service\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Faker\Factory as FakerFactory;

class ForgotPasswordTest extends \PHPUnit_Extensions_Database_TestCase {

    /**
     * @var DbAdapter
     */
    protected $dbAdapter;

    /**
     * @var TableGateway
     */
    protected $gateway;

    /**
     * @var ForgotPasswordService
     */
    protected $service;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setUp() {
        parent::setUp();

        $this->gateway = new TableGateway(
        'zufp_reset', $this->getAdapter()
        );

        $doctrineConfig = include __DIR__ . '/../../doctrine.php';
        $serviceManager = new ServiceManager($doctrineConfig['service_manager']);
        $serviceManager->setService('Configuration', $doctrineConfig);

        $emFactory = new EntityManagerFactory('orm_default');
        $this->entityManager = $emFactory($serviceManager, EntityManager::class);

        $annotations = __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php';
        AnnotationRegistry::registerFile($annotations);

        $zfcUserOptions = new ZfcUserOptions;
        $zfcUserOptions->setPasswordCost(4); // quickest
        $this->service = new ForgotPasswordService(
        $zfcUserOptions, $this->entityManager->getRepository(Reset::class),
        new Reset(new Generator)
        );
    }

    protected function getAdapter() {
        if ($this->dbAdapter) {
            return $this->dbAdapter;
        }
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        $this->dbAdapter = new DbAdapter($config);
        return $this->dbAdapter;
    }

    protected function getConnection() {
        return $this->createDefaultDBConnection($this->getAdapter()->getDriver()->getConnection()->getResource());
    }

    protected function getDataSet() {
        return $this->createFlatXMLDataSet(__DIR__ . '/data/reset-seed.xml');
    }

    public function testSetup() {
        $this->assertNotEmpty($this->gateway->select(['user' => 1, 'nonce' => 'AAAAAAAA']));
        $this->assertNotEmpty($this->gateway->select(['user' => 2, 'nonce' => 'BBBBBBBB']));
    }

    /**
     * @dataProvider dataFindResetSuccess
     */
    public function testFindResetSuccess($userId, $nonce, $resetId) {
        $resetOpt = $this->service->findReset($userId, $nonce);
        $this->assertInstanceOf(\PhpOption\Some::class, $resetOpt);
        foreach ($resetOpt as $reset) {
            $this->assertEquals($userId, $reset->getUser());
            $this->assertEquals($nonce, $reset->getNonce());
            $this->assertEquals($resetId, $reset->getId());
            return;
        }
        $this->fail();
    }

    public static function dataFindResetSuccess() {
        return [
            [1, 'AAAAAAAA', 1],
            [2, 'BBBBBBBB', 2],
        ];
    }

    /**
     * @dataProvider dataFindResetFailure
     */
    public function testFindResetFailure($user, $nonce) {
        $this->assertInstanceOf(
        \PhpOption\None::class, $this->service->findReset($user, $nonce)
        );
    }

    public static function dataFindResetFailure() {
        return [
            [1, 'AAAAAAA'],
            [2, 'AAAAAAAA'],
            [3, 'noreset'],
        ];
    }

    /**
     * @dataProvider dataRemoveResets
     */
    public function testRemoveResets($userId, $hasRecords) {
        $user = new User;
        $user->setId($userId);
        $criteria = ['user' => $user->getId()];
        if ($hasRecords) {
            $this->assertNotEmpty($this->gateway->select($criteria));
        }
        $this->service->removeResets($user);
        $this->assertEmpty($this->gateway->select($criteria));
    }

    public static function dataRemoveResets() {
        return [
            [1, true],
            [2, true],
            [3, false],
            [999, false],
        ];
    }

    /**
     * @dataProvider dataResetPassword
     */
    public function testResetPassword(
    $userId, $nonce, $password, $expectedSuccess, $hasReset = true
    ) {
        $user = $this->entityManager->find(User::class, $userId);
        $origUser = clone $user;
        $this->service->resetPassword($user, $nonce, $password);
        $user = $this->entityManager->find(User::class, $userId); // refresh

        if ($expectedSuccess) {
            $this->assertNotSame($origUser->getPassword(), $user->getPassword());
            $this->assertEmpty($this->gateway->select(['user' => $userId]));
            return;
        }
        $this->assertSame($origUser->getPassword(), $user->getPassword());

        if ($hasReset) {
            $this->assertNotEmpty($this->gateway->select(['user' => $userId]));
        }
    }

    public static function dataResetPassword() {
        $faker = FakerFactory::create();
        return [
            [1, 'AAAAAAAA', $faker->randomNumber(8), true],
            [1, 'junk', $faker->randomNumber(8), false],
            [2, 'BBBBBBBB', $faker->randomNumber(8), true],
            [3, 'noreset', $faker->randomNumber(8), false, false],
        ];
    }

    /**
     * @dataProvider dataCreateReset
     */
    public function testCreateReset($userId, $expectedCount) {
        $reset = $this->service->createReset($userId);
        $this->assertEquals($reset->getUser(), $userId);
        $this->assertTrue(strlen($reset->getNonce()) == Generator::LENGTH);
        $this->service->createReset($userId);
        $this->assertCount(
        $expectedCount, $this->gateway->select(['user' => $userId])
        );
    }

    public static function dataCreateReset() {
        return [
            [1, 3],
            [2, 4],
            [3, 2],
            [999, 2],
        ];
    }

}
