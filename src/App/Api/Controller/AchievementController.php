<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Request\CriteriaHandler;
use App\Api\Response\ResponseSerializer;
use Component\Engine\Engine;

/**
 * @Route("/achievements")
 */
class AchievementController extends AbstractController
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "demo-achievement-01",
     *     "links": {
     *         "self": "http://example.org/api/achievements/demo-achievement-01/",
     *         "actions": ["http://example.org/api/actions/demo-action/"]
     *     }
     * }, {
     *     "name": "demo-achievement-01",
     *     "links": {
     *         "self": "http://example.org/api/achievements/demo-achievement-01/",
     *         "actions": ["http://example.org/api/actions/demo-action/"]
     *     }
     * }]
     * ```.
     *
     * @Route(
     *     "/",
     *     name="api_achievement_index",
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Achievements",
     *     resource=true,
     *     description="Returns a collection of all known Achievements",
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
        $achievementDefinitions = $engine->findAchievementDefinitionAny()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $achievementDefinitions,
            ['achievement.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "demo-achievement-01",
     *     "links": {
     *         "self": "http://example.org/api/achievements/demo-achievement-01/",
     *         "actions": ["http://example.org/api/actions/demo-action/"]
     *     }
     * }
     * ```.
     *
     * @Route(
     *     "/{name}/",
     *     name="api_achievement_show",
     *     requirements={"name" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Achievements",
     *     resource=true,
     *     description="Returns an Achievement identified by its name property",
     *     requirements={
     *         {
     *             "name"="name",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the achievement is not found"
     *     }
     * )
     */
    public function showAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $name
    ): Response {
        $achievementDefinitions = $engine->findAchievementDefinitionBy(['name' => $name]);
        if ($achievementDefinitions->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $serializer->createResponse(
            $achievementDefinitions->first(),
            ['achievement.show']
        );
    }
}
