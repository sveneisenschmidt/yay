<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/achievements")
 */
class AchievementController extends ApiController
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "yay.goal.demo_goal_1",
     *     "links": {
     *         "self": "http://example.org/api/achievements/yay.goal.demo_goal_1",
     *         "actions": ["http://example.org/api/actions/yay.action.demo_action"]
     *     }
     * }, {
     *     "name": "yay.goal.demo_goal_2",
     *     "links": {
     *         "self": "http://example.org/api/achievements/yay.goal.demo_goal_2",
     *         "actions": ["http://example.org/api/actions/yay.action.demo_action"]
     *     }
     * }]
     * ```
     *
     * @Method("GET")
     *
     * @Route(
     *     "/",
     *     name="achievement_index"
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
    public function indexAction(): Response
    {
        return $this->respond(
            $this->getEngine()->findGoalDefinitionAny()->toArray(),
            ['achievement.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "yay.goal.demo_goal_1",
     *     "links": {
     *         "self": "http://example.org/api/achievements/yay.goal.demo_goal_1",
     *         "actions": ["http://example.org/api/actions/yay.action.demo_action"]
     *     }
     * }
     * ```
     *
     * @Method("GET")
     *
     * @Route(
     *     "/{name}",
     *     name="achievement_show",
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
     *
     */
    public function showAction(string $name): Response
    {
        $goalDefinitions = $this->getEngine()->findGoalDefinitionBy(['name' => $name]);
        if ($goalDefinitions->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $this->respond($goalDefinitions->first(), ['achievement.show']);
    }
}
