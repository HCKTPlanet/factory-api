<?php

namespace HcktPlanet\FoundationBundle\Tests\Controller;

use HcktPlanet\FoundationBundle\Entity\User;
use HcktPlanet\FoundationBundle\Tests\WebTestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        // add some users to database
        $this->addFixture('HcktPlanet\FoundationBundle\Tests\DataFixtures\ORM\LoadUserData');
        $this->runFixtures();
    }


    /**
     * Happy path for GET /users
     */
    public function testGetUsers()
    {
        // GIVEN setup

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
            $username = 'user' . ($i + 300);
            $this->assertEquals($username, $json[$i]['username']);
            $this->assertEquals($username . '@mailtrap.io', $json[$i]['email']);
        }
    }

    /**
     * Happy path for GET /user/{usernameOrEmail} using username
     */
    public function testGetUserByUsername()
    {
        // GIVEN setup

        // WHEN requesting for user with username
        $client = static::createClient();
        $client->request('GET', '/users/user300', array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN
        // must have an OK response
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // json object keys are correct
        $this->assertSame($this->getExpectedSerializationKeys(), array_keys($json));

        // and username must have correct data
        $username = 'user300';
        $this->assertEquals($username, $json['username']);
        $this->assertEquals($username, $json['username_canonical']);
        $this->assertEquals($username . '@mailtrap.io', $json['email']);
        $this->assertEquals($username . '@mailtrap.io', $json['email_canonical']);
        $this->assertEquals('p@ssw0rd', $json['password']);
    }
    /**
     * Happy path for GET /user/{usernameOrEmail} using email
     */
    public function testGetUserByEmail()
    {
        // GIVEN setup

        // WHEN requesting for user with username
        $client = static::createClient();
        $client->request('GET', '/users/user300@mailtrap.io', array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN
        // must have an OK response
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // json object keys are correct
        $this->assertSame($this->getExpectedSerializationKeys(), array_keys($json));

        // and username must have correct data
        $username = 'user300';
        $this->assertEquals($username, $json['username']);
        $this->assertEquals($username, $json['username_canonical']);
        $this->assertEquals($username . '@mailtrap.io', $json['email']);
        $this->assertEquals($username . '@mailtrap.io', $json['email_canonical']);
        $this->assertEquals('p@ssw0rd', $json['password']);
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

    /** Assert that a password is correct
     * @param $userJson array JSON serialization of User entity
     * @param $expected string The expected password
     */
    private function assertPassword($userJson, $expected)
    {

        $user = new User();
        $factory = $this->getContainer()->get('security.encoder_factory');

        /** @var $factory EncoderFactory */
        $encoder = $factory->getEncoder($user);
        $this->assertTrue($encoder->isPasswordValid($userJson['password'], $expected, $userJson['salt']));
    }
}
