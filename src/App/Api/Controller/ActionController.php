<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Request\CriteriaHandler;
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
     * @Route(
     *     "/",
     *     name="api_action_index",
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Actions",
     *     resource=true,
     *     description="Returns a collection of all known Actions",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     },
     *     filters={
     *         {"name"="limit", "dataType"="int", "pattern"="0-9"},
     *         {"name"="offset", "dataType"="int", "pattern"="0-9"},
     *         {"name"="order[$field]", "dataType"="string", "pattern"="ASC|DESC"},
     *         {"name"="filter[$field]", "dataType"="string"},
     *         {"name"="filter[$field:eq]", "dataType"="string"},
     *         {"name"="filter[$field:neq]", "dataType"="string"},
     *         {"name"="filter[$field:gt]", "dataType"="string"},
     *         {"name"="filter[$field:lt]", "dataType"="string"},
     *         {"name"="filter[$field:gte]", "dataType"="string"},
     *         {"name"="filter[$field:lte]", "dataType"="string"},
     *     }
     * )
     */
    public function indexAction(
        Request $request,
        Engine $engine,
        CriteriaHandler $handler,
        ResponseSerializer $serializer
    ): Response {
        $actionDefinitions = $engine->findActionDefinitionAny()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $actionDefinitions,
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
     * @Route(
     *     "/{name}/",
     *     name="api_action_show",
     *     requirements={"name" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
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
     */
    public function showAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $name
    ): Response {
        $actionDefinitions = $engine->findActionDefinitionBy(['name' => $name]);
        if ($actionDefinitions->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $serializer->createResponse($actionDefinitions->first(), ['action.show']);
    }
}
