<?php

namespace HcktPlanet\FoundationBundle\Controller;

use HcktPlanet\FoundationBundle\Entity\User;
use HcktPlanet\FoundationBundle\Entity\UserManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends HcktPlanetRestController
{
    /**
     * Get a list of users
     *
     * @ApiDoc(
     *    description = "Get a list of users",
     *    section = "User",
     *
     *    filters={
     *      {"name"="username", "dataType"="string", "required"=false, "description"="Username to search by"}
     *    },
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User",
     *      "groups" = {"user"}
     *    },
     *
     *    statusCodes={
     *      200 = "Successfully returned a list of users (can be an empty list)",
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $userManager = $this->getManager();
        $users = $userManager->findUsers();

        return $this->createSuccessResponse($users);
    }

    /**
     * Get a user
     *
     * @ApiDoc(
     *    description = "Get a user",
     *    section = "User",
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User",
     *      "groups" = {"user"}
     *    },
     *
     *    statusCodes={
     *      200 = "Successfully returned a list of users",
     *      404 = "User Not Found"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users/{usernameOrEmail}")
     */
    public function getUserAction($usernameOrEmail)
    {
        $userManager = $this->getManager();
        $user = $userManager->findUserByUsernameOrEmail($usernameOrEmail);

        if (!$user) {
            $response = new Response();
            $response->setStatusCode(404);
            return $response;
        }

        return $this->createSuccessResponse($user);
    }

    /**
     * Creates a user
     *
     * @ApiDoc(
     *    description = "Creates a user and returns it's id",
     *    section = "User",
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User"
     *    },
     *
     *    statusCodes={
     *      200 = "Successfully created a user",
     *      400 = "Incorrect or missing parameters. See error message for details."
     *    },
     *
     *    parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="User's unique identifier"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="User's e-mail address"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="User's password"}
     *    },
     * )
     *
     * #TODO #@Security("has_role('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $userManager = $this->getManager();

        /** @var $user User */
        $user = $userManager->createUser();

        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));

        $user->setEnabled(true);
        $userManager->save($user);

        return $this->createSuccessResponse($user);
    }


    /**
     * Updates a user
     *
     * @ApiDoc(
     *    description = "Updates a user",
     *    section = "User",
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function putUsersAction($id)
    {

    }

    /**
     * Deletes a user
     *
     * @ApiDoc(
     *    description = "Deletes a user",
     *    section = "User"
     *
     * )
     *
     * @Rest\View()
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUsersAction($id)
    {

    }

    /**
     * @return UserManager
     */
    protected function getManager()
    {
        return $this->container->get('hckt_planet_foundation.user_manager');
    }
}
