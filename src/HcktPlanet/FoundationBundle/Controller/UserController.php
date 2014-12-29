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
     *    parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="User's unique identifier"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="User's e-mail address"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="User's password"}
     *    },
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

        // TODO: User 'Form' method
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));

        $user->setEnabled(true);
        $userManager->updateUser($user);

        return $this->createSuccessResponse($user);
    }


    /**
     * Updates a user
     *
     * @ApiDoc(
     *    description = "Updates a user",
     *    section = "User",
     *
     *    parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="User's id"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="User's e-mail address"},
     *    },
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Put("/users")
     */
    public function putUsersAction(Request $request)
    {
        $userManager = $this->getManager();

        $user = $userManager->findUserBy(array(
            'id' => $request->get('id')
        ));

        // TODO: User 'Form' method
        $user->setEmail($request->get('email'));

        $userManager->updateUser($user);
        return $this->createSuccessResponse($user);
    }

    /**
     * Deletes a user
     *
     * @ApiDoc(
     *    description = "Deletes a user",
     *    section = "User",
     *
     *    statusCodes={
     *      200 = "Successfully deleted the user",
     *      404 = "User Not Found"
     *    }
     * )
     *
     * @Rest\View()
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUsersAction($id)
    {
        $userManager = $this->getManager();

        /** @var $user User */
        $user = $userManager->findUserBy(array('id' => $id));

        if (!$user) {
            $response = new Response();
            $response->setStatusCode(404);
            return $response;
        }

        $user->setEnabled(false);
        $userManager->updateUser($user);

        return $this->createSuccessResponse();

    }

    /**
     * @return UserManager
     */
    protected function getManager()
    {
        return $this->container->get('hckt_planet_foundation.user_manager');
    }
}
