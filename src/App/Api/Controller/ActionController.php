<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Response\ResponseSerializer;
use Component\Engine\Engine;
use Component\Entity\Collection;

/**
 * @Route("/actions")
 */
class ActionController extends Controller
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "yay.action.demo_action",
     *     "links": {
     *         "self": "http://example.org/api/actions/yay.action.demo_action/",
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     *
     * @Route(
     *     "/",
     *     name="api_action_index"
     * )
     *
     * @ApiDoc(
     *     section="Actions",
     *     resource=true,
     *     description="Returns a collection of all known Actions",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     *
     * @return Response
     */
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        return $serializer->createResponse(
            $engine->findActionDefinitionAny()->toArray(),
            ['action.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "yay.action.demo_action",
     *     "links": {
     *         "self": "http://example.org/api/actions/yay.action.demo_action/",
     *     }
     * }
     * ```.
     *
     * @Method("GET")
     *
     * @Route(
     *     "/{name}/",
     *     name="api_action_show",
     *     requirements={"name" = "[A-Za-z0-9\-\_\.]+"}
     * )
     *
     * @ApiDoc(
     *     section="Actions",
     *     resource=true,
     *     description="Returns an Action identified by its name property",
     *     requirements={
     *         {
     *             "name"="name",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the action is not found"
     *     }
     * )
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     * @param string             $username
     *
     * @return Response
     */
    public function showAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $name
    ): Response {
        $achievementDefinitions = $engine->findActionDefinitionBy(['name' => $name]);
        if ($achievementDefinitions->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $serializer->createResponse($achievementDefinitions->first(), ['action.show']);
    }
}
