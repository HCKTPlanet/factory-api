<?php

namespace HcktPlanet\FoundationBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     *      400 = "Validation errors, errors list is returned under 'errors' key",
     *      403 = "Returned when user makes too many requests or violates captcha"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $users = array();
        return $users;
    }

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
     *      400 = "Validation errors, errors list is returned under 'errors' key",
     *      403 = "Returned when user makes too many requests or violates captcha"
     *    }
     *
     * )
     *
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        $users = array();
        return $users;
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
