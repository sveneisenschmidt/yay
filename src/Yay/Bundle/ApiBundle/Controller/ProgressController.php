<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Yay\Bundle\ApiBundle\Response\ResponseSerializer;
use Yay\Component\Engine\Engine;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

/**
 * @Route("/progress")
 */
class ProgressController
{
    /**
     * **Example Request (1):**
     * ```json
     * {
     *     "player": "jane.doe",
     *     "action": "yay.action.demo_action"
     * }
     * ```.
     *
     * **Example Request (2):**
     * ```json
     * {
     *     "player": "jane.doe",
     *     "actions": [
     *         "yay.action.demo_action",
     *         "yay.action.demo_action",
     *         "yay.action.demo_action",
     *         "yay.action.demo_action"
     *     ]
     * }
     * ```
     *
     * **Example Response:**
     * ```json
     * [{
     *     "name": "demo-achievement-01",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-01"
     *     }
     * }, {
     *     "name": "demo-achievement-02",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-02"
     *     }
     * }]
     * ```
     *
     * @Method("POST")
     * @Route(
     *     "/",
     *     name="api_progress_submit"
     * )
     * @ApiDoc(
     *     section="Progress of an Player",
     *     resource=true,
     *     description="Submit a payload to update a users progress",
     *     requirements={
     *         {
     *             "name"="player",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Username of the Player to progress"
     *         },
     *         {
     *             "name"="action",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Action that the Player has made progress in"
     *         },
     *         {
     *             "name"="actions",
     *             "dataType"="array",
     *             "requirement"="Array<[a-z\.\-\_]+>",
     *             "description"="Actions that the Player has made progress in"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = {
     *             "Returned when the player is not found",
     *             "Returned when on of the actions does can not found"
     *         }
     *     }
     * )
     * @ParamConverter(
     *     name="username",
     *     options={"field"="player"},
     *     converter="JsonField"
     * )
     * @ParamConverter(
     *     name="action",
     *     converter="JsonField"
     * )
     * @ParamConverter(
     *     name="actions",
     *     converter="JsonField"
     * )
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     * @param string             $username
     * @param string|null        $action
     * @param array              $actions
     *
     * @return Response
     */
    public function submitAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username,
        string $action = null,
        array $actions = []
    ): Response {
        if (!empty($action)) {
            $actions[] = $action;
        }

        if (empty($action) && empty($actions)) {
            throw $this->createNotFoundException();
        }

        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        /** @var PlayerInterface $player */
        $player = $players->first();
        $personalActionCollection = new PersonalActionCollection();

        foreach ($actions as $action) {
            $actionDefinitions = $engine->findActionDefinitionBy(['name' => $action]);
            if ($actionDefinitions->isEmpty()) {
                continue;
            }

            $personalActionCollection->add(
                new PersonalAction($player, $actionDefinitions->first())
            );
        }

        $personalAchievements = $engine->advance($player, $personalActionCollection);

        return $serializer->createResponse(
            $personalAchievements,
            ['progress.submit']
        );
    }
}
