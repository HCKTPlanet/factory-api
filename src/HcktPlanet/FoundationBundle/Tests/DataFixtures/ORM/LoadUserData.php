<?php
/**
 * Created By: Luciano Bargmann
 * Date: 29/12/14
 * Time: 08:53
 * © HCKTPlanet Informatica Ltda
 * All Rights Reserved. Unauthorized copies of this code file may subject you to civil and criminal liability.
 */

namespace HcktPlanet\FoundationBundle\Tests\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HcktPlanet\FoundationBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //Create 10 users
        for ($id = 300; $id < 310; ++$id) {
            $user = $this->createTestUser('user' . $id, 'p@ssw0rd');
            $manager->persist($user);
        }

        $manager->flush();
    }

    /** Creates a user with basic information
     * @param $username
     * @param $password
     * @return User
     */
    protected function createTestUser($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($username . '@mailtrap.io');
        $user->setEnabled(true);

        return $user;
    }

}
