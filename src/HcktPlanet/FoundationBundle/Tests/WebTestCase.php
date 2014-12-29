<?php
/**
 * Created By: Luciano Bargmann
 * Date: 29/12/14
 * Time: 15:09
 * © HCKTPlanet Informatica Ltda
 * All Rights Reserved. Unauthorized copies of this code file may subject you to civil and criminal liability.
 */

namespace HcktPlanet\FoundationBundle\Tests;

use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase as LiipWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCase extends LiipWebTestCase
{
    protected $fixtures = array();

    /**
     * @var EntityManager
     */
    protected $em = null;

    public function setUp()
    {
        $this->em = $this->getEntityManager();
    }

    /**
     * @return Client
     */
    public function createTestClient()
    {
        $client = static::createClient();
        $client->followRedirects();
        return $client;
    }

    /**
     * @param $fixture
     */
    protected function addFixture($fixture)
    {
        $this->fixtures[] = $fixture;
    }

    /**
     * Loads added fixtures
     */
    public function runFixtures()
    {
        $this->loadFixtures($this->fixtures);
    }

    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getRepository($class)
    {
        return $this->getEntityManager()->getRepository($class);
    }


}
