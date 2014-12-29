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
        // GIVEN user search data
        $expectedUsername = 'user300';
        $expectedEmail = 'user300@mailtrap.io';
        $expectedPassword = 'p@ssw0rd';

        // ASSERT
        $this->assertGetUser($expectedUsername, $expectedEmail, $expectedPassword);
    }

    /**
     * Happy path for GET /user/{usernameOrEmail} using email
     */
    public function testGetUserByEmail()
    {
        // GIVEN user search data
        $expectedUsername = 'user300';
        $expectedEmail = 'user300@mailtrap.io';
        $expectedPassword = 'p@ssw0rd';

        // ASSERT
        $this->assertGetUser($expectedUsername, $expectedEmail, $expectedPassword, true);
    }

    /**
     * User Not Found test for GET /user/{usernameOrEmail} using inexistent username
     */
    public function testGetUserNotFound()
    {
        // GIVEN user search data
        $expectedUsername = 'sakura_chan';

        // WHEN requesting for user with username
        $response = $this->getUser($expectedUsername);

        // THEN must have a NOT FOUND response with empty content
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    /**
     * Update User with PUT /users
     */
    public function testPutUser()
    {
        // GIVEN we find user300 and want to change its email
        $response = $this->getUser('user300');
        $json = json_decode($response->getContent(), true);

        $parameters = array(
            'id' => $json['id'],
            'email' => 'sakura_chan@konoha.jp'
        );

        // WHEN updating user email
        $client = static::createClient();
        $client->request('PUT', '/users', $parameters, array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN
        // must have OK response
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // and email must have been updated
        $this->assertEquals($parameters['email'], $json['email']);
        $this->assertEquals($parameters['email'], $json['email_canonical']);

        // and must be able to find by email
        $this->assertGetUser('user300', $parameters['email'], 'p@ssw0rd', true);
    }

    /**
     * Happy path for creating a user via POST /users
     */
    public function testPostUser() {
        // GIVEN the new user data
        $parameters = array(
            'username' => 'joe',
            'email' => 'joe@test.com',
            'password' => 'notsecure'
        );

        // WHEN posting new user
        $client = static::createClient();
        $client->request('POST', '/users', $parameters, array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN user must have been created
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // with all keys
        $this->assertSame($this->getExpectedSerializationKeys(), array_keys($json));

        // and has correct username, email and password:
        $this->assertEquals($parameters['username'], $json['username']);
        $this->assertEquals($parameters['username'], $json['username_canonical']);
        $this->assertEquals($parameters['email'], $json['email']);
        $this->assertEquals($parameters['email'], $json['email_canonical']);
        $this->assertEquals($parameters['password'], $json['password']);

        // and ensure user is on database
        $this->assertGetUser($parameters['username'], $parameters['email'], $parameters['password']);
    }

    /**
     * Update User with DELETE /users/{id}
     */
    public function testDeleteUser()
    {
        // GIVEN we find user300 and want to delete it
        $response = $this->getUser('user300');
        $json = json_decode($response->getContent(), true);
        $this->assertTrue($json['enabled']);

        // WHEN deleting user
        $client = static::createClient();
        $client->request('DELETE', '/users/' . $json['id'], array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN
        // must have OK response
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // and user must have been disabled
        $response = $this->getUser('user300');
        $json = json_decode($response->getContent(), true);
        $this->assertFalse($json['enabled']);
    }

    /**
     * User Not Found test for GET /user/{usernameOrEmail} using inexistent username
     */
    public function testDeleteUserNotFound()
    {
        // GIVEN id doesnt exist
        $id = 1337;

        // WHEN requesting for user with username
        $client = static::createClient();
        $client->request('DELETE', '/users/' . $id, array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();

        // THEN must have a NOT FOUND response with empty content
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
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

    /** Request a user from database and do basic assertions
     * @param $expectedUsername
     * @param $expectedEmail
     * @param $expectedPassword
     * @param bool $getByEmail
     */
    protected function assertGetUser($expectedUsername, $expectedEmail, $expectedPassword, $getByEmail = false)
    {
        // WHEN requesting for user with username
        $uri = $getByEmail ? $expectedEmail : $expectedUsername;
        $response = $this->getUser($uri);

        // THEN
        // must have an OK response
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        // json object keys are correct
        $this->assertSame($this->getExpectedSerializationKeys(), array_keys($json));

        // and username must have correct data
        $this->assertEquals($expectedUsername, $json['username']);
        $this->assertEquals($expectedUsername, $json['username_canonical']);
        $this->assertEquals($expectedEmail, $json['email']);
        $this->assertEquals($expectedEmail, $json['email_canonical']);
        $this->assertEquals($expectedPassword, $json['password']);
    }

    /**
     * @param $username
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function getUser($username)
    {
        $client = static::createClient();
        $uri = '/users/' . $username;
        $client->request('GET', $uri, array(), array(), array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest',));
        $response = $client->getResponse();
        return $response;
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
