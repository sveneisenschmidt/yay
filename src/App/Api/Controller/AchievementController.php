<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Response\ResponseSerializer;
use Component\Engine\Engine;

/**
 * @Route("/achievements")
 */
class AchievementController extends Controller
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
     * @Method("GET")
     *
     * @Route(
     *     "/",
     *     name="api_achievement_index"
     * )
     *
     * @ApiDoc(
     *     section="Achievements",
     *     resource=true,
     *     description="Returns a collection of all known Achievements",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     */
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        return $serializer->createResponse(
            $engine->findAchievementDefinitionAny()->toArray(),
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
     * @Method("GET")
     *
     * @Route(
     *     "/{name}/",
     *     name="api_achievement_show",
     *     requirements={"name" = "[A-Za-z0-9\-\_\.]+"}
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
