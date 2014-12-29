<?php

namespace HcktPlanet\FoundationBundle\Controller;

use FOS\UserBundle\Entity\UserManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *    statusCodes={
     *      200 = "Successfully returned a list of users (can be empty list as well)",
     *      404 = "No user found with the given search criteria"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {

        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        if (count($users) === 0) {
            $response = new Response();
            $response->setStatusCode(404);

            return $response;
        }

        return $users;
    }

    /**
     * Get a user
     *
     * @ApiDoc(
     *    description = "Get a user",
     *
     *    statusCodes={
     *      200 = "Successfully returned a list of users (can be empty list as well)",
     *      404 = "User not found"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        if (count($user) === 0) {
            $response = new Response();
            $response->setStatusCode(404);

            return $response;
        }

        return $user;
    }

    /**
     * Creates a user
     *
     * @ApiDoc(
     *    description = "Creates a user",
     * )
     *
     * @Rest\View()
     * @Rest\Post("/users")
     */
    public function postUsersAction()
    {

    }


    /**
     * Updates a user
     *
     * @ApiDoc(
     *    description = "Updates a user",
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
     * )
     *
     * @Rest\View()
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUsersAction($id)
    {

    }
}
