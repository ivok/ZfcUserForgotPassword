<?php

namespace ZfcUserForgotPassword\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use PhpOption\Option;
use ZfcUserForgotPassword\Exception\Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="zufp_reset",indexes={
 *   @ORM\Index(columns={"user","nonce"}),
 *   @ORM\Index(columns={"created_on"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Reset {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     */
    protected $id;

    /**
     * Not bound especially tight to the User object.
     * Any way, errant rows shall be cleared out in time.
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true})
     */
    protected $user;

    /**
     * @ORM\Column
     */
    protected $nonce;

    /**
     * @var Option Callable
     */
    protected $nonceGenerator;

    /**
     * @ORM\Column(name="created_on", type="datetime")
     */
    protected $createdOn;

    public function __construct(Callable $nonceGenerator = null) {
        $this->nonceGenerator = Option::fromValue($nonceGenerator);
    }

    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = intval($user);
    }

    public function setNonceGenerator(Callable $nonceGenerator) {
        $this->nonceGenerator = Option::fromValue($nonceGenerator);
    }

    /**
     * @ORM\PrePersist
     */
    public function getNonce() {
        if (empty($this->nonce)) {
            foreach ($this->nonceGenerator as $generator) {
                $this->nonce = (string) $generator();
            }
        }
        if (empty($this->nonce)) {
            throw new Exception('Nonce is empty');
        }
        return $this->nonce;
    }

    public function setNonce($nonce) {
        $this->nonce = $nonce;
    }

    /**
     * @ORM\PrePersist
     */
    public function getCreatedOn() {
        if (empty($this->createdOn)) {
            $this->createdOn = new DateTime;
        }
        return clone $this->createdOn;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = clone $createdOn;
    }

}
