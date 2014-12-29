<?php
/**
 * Created By: Luciano Bargmann
 * Date: 29/12/14
 * Time: 05:25
 * © HCKTPlanet Informatica Ltda
 * All Rights Reserved. Unauthorized copies of this code file may subject you to civil and criminal liability.
 */

namespace HcktPlanet\FoundationBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class HcktPlanetRestController extends FOSRestController {

    /** Serializes the result and sends a 200 OK Response
     * @param array $result
     * @param int $statusCode
     * @return Response
     */
    public function createSuccessResponse($result = array(), $statusCode = 200)
    {
        $response = new Response();

        if ($result) {
            $response->setContent($this->get('serializer')->serialize($result, 'json'));
        }

        $response->setStatusCode($statusCode);
        return $response;
    }


    /** Creates a Response when input parameters are not valid
     * @param $errors
     * @return Response
     */
    public function createBadRequestResponse($errors)
    {
        $response = new Response();
        $response->setContent($this->get('serializer')->serialize($errors, 'json'));
        $response->setStatusCode(400);
        return $response;
    }
} 