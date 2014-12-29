<?php

namespace HcktPlanet\FoundationBundle\Controller;

use FOS\UserBundle\Entity\UserManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Get a list of users
     *
     * @ApiDoc(
     *    description = "Get a list of users",
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
        return $users;
    }

    /**
     * Get a user
     *
     * @ApiDoc(
     *    description = "Get a user",
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
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        $userManager = $this->getManager();
        $user = $userManager->findUserByUsernameOrEmail($id);

        if (count($user) === 0) {
            $response = new Response();
            $response->setStatusCode(404);
            $response->setContent("User Not Found");

            return $response;
        }

        return $user;
    }

    /**
     * Creates a user
     *
     * @ApiDoc(
     *    description = "Creates a user and returns it's id",
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User",
     *      "groups" = {"user"}
     *    },
     *
     *    statusCodes={
     *      200 = "Successfully created a user",
     *      404 = "No user found with the given search criteria"
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
        $user = $userManager->createUser();

        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));

        $user->addRole("ROLE_USER");
        $user->setEnabled(true);





    }


    /**
     * Updates a user
     *
     * @ApiDoc(
     *    description = "Updates a user",
     *
     *    output={
     *      "class" = "HcktPlanet\FoundationBundle\Entity\User",
     *      "groups" = {"user"}
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
     *
     *    output={
     *      "groups" = {"user"}
     *    }
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
        return $this->container->get('fos_user.user_manager');
    }
}
