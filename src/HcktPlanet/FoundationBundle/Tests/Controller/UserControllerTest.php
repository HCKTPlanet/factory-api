<?php

namespace HcktPlanet\FoundationBundle\Tests\Controller;

use HcktPlanet\FoundationBundle\Tests\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }


    public function testGet()
    {
        // GIVEN we have some users in database
        $this->addFixture('HcktPlanet\FoundationBundle\Tests\DataFixtures\ORM\LoadUserData');
        $this->runFixtures();

        // WHEN requesting for users list
        $client = static::createClient();
        $client->request('GET', '/users', array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN
        // must have an OK response
        $this->assertEquals(200, $response->getStatusCode());

        // must have 10 users
        $json = json_decode($response->getContent(), true);
        $this->assertEquals(10, count($json));

        // json object keys are correct
        $this->assertSame($this->getExpectedSerializationKeys(), array_keys($json[0]));

        // and that username and email are correct for all users
        for ($i = 0; $i < 10; $i++) {
            $username = 'user' . ($i+300);
            $this->assertEquals($username, $json[$i]['username']);
            $this->assertEquals($username . '@mailtrap.io', $json[$i]['email']);
        }
    }

    /** Gets a list of HcktPlanet\FoundationBundle\Entity\User serialization keys
     * @return array
     */
    private function getExpectedSerializationKeys()
    {
        return array(
            "id",
            "username",
            "username_canonical",
            "email",
            "email_canonical",
            "enabled",
            "salt",
            "password",
            "locked",
            "expired",
            "roles",
            "credentials_expired"
        );
    }
}
