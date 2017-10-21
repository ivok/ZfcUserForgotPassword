<?php

namespace ZfcUserForgotPassword\Service;

use Doctrine\ORM\EntityRepository;
use ZfcUserForgotPassword\Entity\Reset;
use ZfcUser\Entity\User;
use PhpOption\Option;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Options\ModuleOptions as ZfcUserOptions;

class ForgotPassword {

    /**
     * @var ZfcUserOptions
     */
    protected $zfcUserOptions;

    /**
     * @var EntityRepository
     */
    protected $resetRepo;

    /**
     * @var Reset
     */
    protected $resetPrototype;

    public function __construct(
    ZfcUserOptions $zfcUserOptions, EntityRepository $resetRepo,
    Reset $resetPrototype
    ) {
        $this->zfcUserOptions = $zfcUserOptions;
        $this->resetRepo = $resetRepo;
        $this->resetPrototype = $resetPrototype;
    }

    /**
     * @param string $user
     * @return Reset
     */
    public function createReset($user) {
        $reset = clone $this->resetPrototype;
        $reset->setUser($user);
        $this->getEntityManager()->persist($reset);
        $this->getEntityManager()->flush($reset);
        return $reset;
    }

    protected function getEntityManager() {
        return $this->resetRepo->createQueryBuilder('reset')->getEntityManager();
    }

    /**
     * @return Option
     */
    public function findReset($user, $nonce) {
        $match = $this->resetRepo->findBy([
            'user' => $user,
            'nonce' => $nonce,
        ]);
        return Option::fromValue(current($match), false);
    }

    public function removeResets(User $user) {
        $this->resetRepo->createQueryBuilder('reset')
        ->delete()->where('reset.user  = :user')
        ->getQuery()->execute(['user' => $user->getId()]);
    }

    public function resetPassword(User $user, $nonce, $newPassword) {
        foreach ($this->findReset($user->getId(), $nonce) as $reset) {
            $bcrypt = new Bcrypt;
            $bcrypt->setCost($this->getZfcUserOptions()->getPasswordCost());
            $user->setPassword($bcrypt->create($newPassword));
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush($user);
            $this->removeResets($user);
            return true;
        }

        return false;
    }

    public function getZfcUserOptions() {
        return $this->zfcUserOptions;
    }

}
