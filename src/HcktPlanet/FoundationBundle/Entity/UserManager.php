<?php
/**
 * Created By: Luciano Bargmann
 * Date: 29/12/14
 * Time: 05:32
 * © HCKTPlanet Informatica Ltda
 * All Rights Reserved. Unauthorized copies of this code file may subject you to civil and criminal liability.
 */

namespace HcktPlanet\FoundationBundle\Entity;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager extends \FOS\UserBundle\Entity\UserManager {

    public function __construct(EncoderFactoryInterface $encoderFactory,
                                CanonicalizerInterface $usernameCanonicalizer,
                                CanonicalizerInterface $emailCanonicalizer,
                                EntityManager $em,
                                $class)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em, $class);

        $this->em = $em;
    }

    /**
     * Persists a User object to the database
     */
    public function save(User $user) {
        $this->em->persist($user);
        $this->em->flush();
    }
} 