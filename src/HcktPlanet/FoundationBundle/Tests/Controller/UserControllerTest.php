<?php

namespace HcktPlanet\FoundationBundle\Tests\Controller;

use HcktPlanet\FoundationBundle\Tests\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->addFixture('HcktPlanet\FoundationBundle\Tests\DataFixtures\ORM\LoadUserData');
    }


    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/users',
            array(),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

        $this->assertNotNull($crawler);
    }
}
